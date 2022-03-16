<?php
    session_start();
    
    // Check if the user is logged in
    if (isset($_SESSION["username"]) && isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
        // Connect to the database
        require_once "config.php";  

        // Get watchlist data from the database
        $sql = "SELECT * FROM `watchLists` WHERE username='".$_SESSION["username"]."';";
        if ($result = $mysqli->query($sql)) {
            // Check if there is watchlist for the user   
            if ($data = $result->fetch_all(MYSQLI_BOTH)) {  
                if (count($data) == 1) {
                    $movies = $data[0]["titles"];
                    $types = $data[0]["types"]; 

                    // Get the values from http request
                    if (isset($_GET["id"]) && isset($_GET["type"])) {
                        $movie = $_GET["id"];
                        $movie_type = $_GET["type"];    

                        if ($movies == '' && $types == '') {
                            $movies .= "'" . $movie . "'";
                            $types .= $movie_type;
                        } else {
                            if (strpos($movies, $movie) === False) {
                                $movies .= ", " . "'" . $movie . "'";
                                $types .= ", " . $movie_type;
                            }
                        }
                        
                        // Update the database
                        $sql_update = "UPDATE `watchLists` SET titles=\"".$movies."\", types='".$types."' WHERE username='".$_SESSION["username"]."';";
                        $result_update = $mysqli->query($sql_update) or die($mysqli->error);
                    }
                }
            // Create an empty watchlist if does not exist
            } else {
                $sql = "INSERT INTO watchLists (username, titles, types, watchedMovies, watchedEpisodes) VALUES 
                    ('".$_SESSION["username"]."', '', '', '', '')";
                $result = $mysqli->query($sql) or die($mysqli->error);
                header("location: add_to_list.php?id=".$_GET["id"]."&type=".$_GET["type"]);
                exit();
            }
        } else {
            die($mysqli->error);
        } 
        
        // Update the watchlist in the local storage of the user
        $sql = "SELECT * FROM `watchLists` WHERE username='".$_SESSION["username"]."';";
        $result = $mysqli->query($sql) or die($mysqli->error);

        if ($data = $result->fetch_array(MYSQLI_ASSOC)) {
            $json = json_encode($data);
            echo "<script type='text/javascript'>
                console.log('Watchlist Saved');
                localStorage.setItem('watchlist', `". $json ."`);
                </script>";
        }   
        $mysqli->close();
        header("refresh: 0.1; url = watchlist.php");
        exit();

    } else {
        header("location: index.php");
    }
?>