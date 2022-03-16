<?php

// Start the session
session_start();
// Remove the session
session_unset();
session_destroy();

header("location: index.php");
?>