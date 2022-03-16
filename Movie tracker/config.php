<?php
    // Database settings
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'movies';
  
    // Connect to the databse
    $mysqli = @new mysqli(
      $db_host,
      $db_user,
      $db_password,
      $db_db
    );
    
    // Show message in case of error
    if ($mysqli->connect_error) {
      echo 'Errno: '.$mysqli->connect_errno;
      echo '<br>';
      echo 'Error: '.$mysqli->connect_error;
      die('Error: ' . $mysqli->connect_error);
      exit();
    }

    /*
    echo 'Success: A proper connection to MySQL was made.';
    echo '<br>';
    echo 'Host information: '.$mysqli->host_info;
    echo '<br>';
    echo 'Protocol version: '.$mysqli->protocol_version;
    echo '<br>';
    */
?>