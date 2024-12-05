<?php
session_start();  // Start the session

// Unset all session variables
session_unset();

// Destroy the session completely
session_destroy();

// Redirect the user to the login page
header("Location: loginForm.php");
exit();
?>
