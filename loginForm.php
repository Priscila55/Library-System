<?php
session_start(); // Start the session

if (isset($_SESSION['user'])) {
    // If already logged in, redirect to the main page
    header("Location: mainPage.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navBarController.php' ;?>

    <section class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <!--Login form -->
            <form method="POST" action="loginController.php">
                <div class="textbox">
                    <input type="username" name="username" required placeholder="Username">
                </div>
                <div class="textbox">
                    <input type="password" name="password" required placeholder="Password">
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
