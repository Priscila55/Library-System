<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
// Database configuration
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "library_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required fields
    $required_fields = ['username', 'firstname', 'surname', 'addressLine1', 'city', 'mobile', 'password', 'password_confirmation'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("All required fields must be filled. Missing field: " . $field);
        }
    }
    
    // Check password length
    if (strlen($_POST['password']) < 6) {
        die("Password must be at least 6 characters long.");
    }

    // Sanitize and hash inputs
    $userName = $conn->real_escape_string($_POST['username']);
    $firstName = $conn->real_escape_string($_POST['firstname']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $address1 = $conn->real_escape_string($_POST['addressLine1']);
    $address2 = $conn->real_escape_string($_POST['addressLine2']);
    $city = $conn->real_escape_string($_POST['city']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);

    // Check if username already exists
    $checkUsername = "SELECT * FROM users WHERE username = '$userName'";
    $result = $conn->query($checkUsername);

    // If username exists, show error and stop further execution
    if ($result && $result->num_rows > 0) {
        die("Username '$userName' already exists. Please choose another username. <a href='register.php'>Try Again</a> or <a href = 'loginForm.php'>Login</a>");
    }

    // SQL query to insert user data into the database
    $sql = "INSERT INTO users (username, password, firstname, surname, addressLine1, addressLine2, city, mobile)
            VALUES ('$userName', '$password', '$firstName', '$surname', '$address1', '$address2', '$city', '$mobile')";

    // Execute query and handle success or error
    if ($conn->query($sql) === TRUE) {
        echo "New account registered successfully.";
        header("Location: loginForm.php"); // Redirect to login page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "All required fields must be filled.";
}

// Close connection
$conn->close();
?>


</body>
</html>