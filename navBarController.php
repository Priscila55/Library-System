<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!--Button to open the sidebar -->
<button onclick="openNav()" class="openBtn">☰</button>

<!-- Navbar -->
<div id="navBarController" class="sideNav">
<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="mainPage.php" class="mainPage-link">Home</a>
    <a href="loginForm.php" class="loginForm-link">Login</a>
    <a href="register.php" class="register-link">Register</a>
    <a href="reserveBook.php" class="reserve_book-link">Reserve a Book</a>
    <a href="myReservations.php" class="myReservations-link">Reservations</a>

<?php
   if ( isset($_SESSION['user'])): ?>
   <form method = "POST" action = "logout.php">
    <button type = "submit" class = "logout-btn">Log Out</button>
   </form>
   <?php endif; ?>
   </div>

<!--JavaScript for opening and closing the sidebar -->
<script>
    // Function to open the sidebar
    function openNav() {
        document.getElementById("navBarController").style.width = "250px";  // Open sidebar
    }

    // Function to close the sidebar
    function closeNav() {
        document.getElementById("navBarController").style.width = "0";  // Close sidebar
    }
</script>

</body>
</html>
