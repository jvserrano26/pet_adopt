<?php
// Start the session if it's not already started
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page (or any other desired page)
header("Location: index.php");
exit(); // Ensure no further code is executed after redirection
?>