<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reservation Home</title>
    <link rel = "stylesheet" href = "styles.css">
</head>
<body>
<header>
        <h1>Welcome to Book Reservation</h1>
        <p>Unlock a world of books ready for reservation with just one click</p>
</header>

<?php include 'navBarController.php' ;?>>

    <?php

    session_start();

    // connection to the databse 
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "library_system"; 

    // create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if both username and password are set in the POST request
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Get form data
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            // Hash the password for security (though we only need it for registration)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // check if username exists in the database
            $checkUsername = "SELECT * FROM users WHERE username = '$username'";
            $result = $conn->query($checkUsername);
    
            if ($result->num_rows > 0) {
                // Username exists
                $user = $result->fetch_assoc();
    
                // verify password
                if (password_verify($password, $user['Password'])) {
                    $_SESSION['user'] = $user['Username'];
    
                    // Redirect to the main page after successful login
                    header("Location: mainPage.php");
                    exit();
                } else {
                    // Password is incorrect
                    echo "Invalid password. Please try again";
                }
            } else {
                // Username doesn't exist
                echo "No account found with that username. Please <a href='register.php'>register</a>";
            }
        } else {
            // If username or password is missing
            echo "Please fill out both the username and password fields.";
        }
    }
    
    
// Close connection
$conn->close();
?>
</body>
</html>