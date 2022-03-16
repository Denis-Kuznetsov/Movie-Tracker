<?php
    session_start();

    // Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
        // Connect to the database
        require "config.php";

        // Get watchlist from the database
        $sql = "SELECT * FROM `watchLists` WHERE username='".$_SESSION["username"]."';";
        $result = $mysqli->query($sql) or die($mysqli->error);

        if ($data = $result->fetch_array(MYSQLI_ASSOC)) {
            // Encode the watchlist to json string
            $json = json_encode($data);
            // Save to the local storage
            echo "<script type='text/javascript'>
                console.log('Watchlist Saved');
                localStorage.setItem('watchlist', `". $json ."`);
                </script>";
        } else {
            echo "<script type='text/javascript'>
                console.log('Watchlist Saved');
                localStorage.setItem('watchlist', `{}`);
                </script>";
        }

        $mysqli->close();
        header("refresh: 0.1; url = watchlist.php");
        exit();
    }
?>