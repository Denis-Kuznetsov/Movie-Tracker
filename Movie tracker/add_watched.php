<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    require "config.php";

    // Get the watchlist from the database
    $sql = "SELECT * FROM `watchLists` WHERE username='".$_SESSION["username"]."';";

    if ($result = $mysqli->query($sql)) {   
        if ($data = $result->fetch_assoc()) {
            $movies = $data["titles"];
            $types = $data["types"]; 
            $watched = $data["watchedMovies"];
            $watched_episodes = $data["watchedEpisodes"];

            // Get movie id and type from http request
            if (isset($_GET["id"]) && isset($_GET["type"])) {
                $movie = $_GET["id"];
                $movie_type = $_GET["type"]; 

                // Add the movie or series
                if ($movie_type !== "tvEpisode") {
                    if (strpos($watched, $movie)) {
                        $mysqli->close();
                        exit();
                    }

                    if ($watched == "") {
                        $watched = "'" . $movie . "'";
                    } else {
                        $watched .= ", '" . $movie . "'";
                    }
                // Add the tv episode
                } else {
                    if (strpos($watched_episodes, $movie)) {
                        $mysqli->close();
                        exit();
                    }

                    if ($watched_episodes == "") {
                        $watched_episodes = "'" . $movie . "'";
                    } else {
                        $watched_episodes .= ", '" . $movie . "'";
                    }
                }

                // Update the database
                $sql_update = "UPDATE `watchLists` SET watchedMovies=\"".$watched."\", watchedEpisodes=\"".$watched_episodes."\" WHERE username='".$_SESSION["username"]."';";
                $result_update = $mysqli->query($sql_update) or die($mysqli->error);
            }
        }
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