<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true) && $_SESSION["username"] == 'admin') {
    require "download.php";

    require "extract.php";

    require "upload_csv.php";
  
    echo "The database is updated successfully!";
}

header("location: ../index.php");
exit();
?>