<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION["username"]) && isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
    require_once "config.php";

    // Get watchlist from the database
    $sql = "SELECT * FROM `watchLists` WHERE username='".$_SESSION["username"]."';";
    if ($result = $mysqli->query($sql)) {

        if ($data = $result->fetch_assoc()) {
            $movies = explode(", ", $data["titles"]);
            $watched = explode(", ", $data["watchedMovies"]);
            $watched_episodes = explode(", ", $data["watchedEpisodes"]);

            // Get the movie id from the http request
            if (isset($_GET["id"])) {
                $movie = $_GET["id"];
                $movie_type = $_GET["type"];

                // Remove the episode from watched episodes
                if ($movie_type == "tvEpisode") {
                    if (($num = array_search("'".$movie."'", $watched_episodes)) !== false) {
                        array_splice($watched_episodes, $num, 1);
                        $sql_update = "UPDATE `watchLists` SET watchedEpisodes=\"".implode(", ", $watched)."\" WHERE username='".$_SESSION["username"]."';";
                    
                        $result_update = $mysqli->query($sql_update) or die($mysqli->error);
                    }
                // Remove the movie from watched movies
                } else {
                    if (($num = array_search("'".$movie."'", $watched)) !== false) {
                        array_splice($watched, $num, 1);
                        $sql_update = "UPDATE `watchLists` SET watchedMovies=\"".implode(", ", $watched)."\" WHERE username='".$_SESSION["username"]."';";
                    
                        $result_update = $mysqli->query($sql_update) or die($mysqli->error);
                    }
                }
            }
        }
    }
 
    // Update the watchlist in the local storage
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