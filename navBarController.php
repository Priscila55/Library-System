<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Button to open the sidebar -->
<button onclick="openNav()" class="openBtn">☰</button>

<!-- Navbar -->
<div id="navBarController" class="sideNav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="mainPage.php" class="mainPage-link">Home</a>
    <!-- Show Login and Register links if the user is not logged in -->
    <?php if (!isset($_SESSION['user'])): ?>
        <a href="loginForm.php" class="loginForm-link">Login</a>
        <a href="register.php" class="register-link">Register</a>
    <?php endif; ?>
    
    <a href="myReservations.php" class="myReservations-link">Reservations</a>
    <a href="books.php" class="books-link">Books</a>

    <!-- Show Logout link if the user is logged in -->
    <?php if (isset($_SESSION['user'])): ?>
        <a href="logout.php" class="logout-link">Log Out</a>
    <?php endif; ?>
</div>

<!-- JavaScript for opening and closing the sidebar -->
<script>
    // Function to open the sidebar
    function openNav() {
        document.getElementById("navBarController").style.width = "250px";  // Opens the sidebar
    }

    // Function to close the sidebar
    function closeNav() {
        document.getElementById("navBarController").style.width = "0";  // Closes the sidebar
    }
</script>

</body>
</html>
