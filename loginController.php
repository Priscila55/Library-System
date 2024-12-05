<?php
session_start();  // Start the session to store the user info

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'database.php';  // Include your database connection

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to find user based on username
    $sql = "SELECT UserID, Username, Password FROM users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the password matches (assuming the password is hashed)
        if (password_verify($password, $user['Password'])) {
            // Store user info in session
            $_SESSION['user'] = $username;  
            $_SESSION['userID'] = $user['UserID']; 

            // Redirect to mainPage.php after successful login
            header("Location: mainPage.php");
            exit();
        } else {
            // Invalid password - Set error message in session
            $_SESSION['error'] = "Incorrect password. Please try again.";
        }
    } else {
        // User not found - Set error message in session
        $_SESSION['error'] = "No account found with that username. Please check and try again.";
    }

    $stmt->close();

    // Redirect back to login form
    header("Location: loginForm.php");
    exit();
}
?>
