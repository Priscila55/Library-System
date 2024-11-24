<?php 
session_start(); 

session_unset(); // destroy session variables 

session_destroy();

// redirect user to the login page 
header("Location: loginForm.php"); 

exit();
?>