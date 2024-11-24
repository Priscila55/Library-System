<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel = "stylesheet" href = "styles.css">
</head>
<body>
    
<?php include 'navBarController.php' ;?>

<section class = "registerContainer">
    <div class = "register-box">
<form action= "registerController.php" method = "POST">
        <h1>Register</h1>
        <p>Please fill in this form to create an account.</p>
        <hr>

        <label for = "username"><b>Username</b></label>  </label>
        <input type = "text" placeholder = "Enter Username" name = "username" id = "username" required> 

        <label for = "firstname"><b>First Name: </b> </label>
        <input type = "text" placeholder = "Enter First Name" name = "firstname" id = "firstname" required>

        <label for = "surname"><b>Surname: </b></label>
        <input type = "text" placeholder = "Enter Surname" name = "surname" id = "surname" required>

        <label for = "addressLine1"><b>Address Line 1: </b></label>
        <input type = "text" placeholder = "Enter Address Line 1" name = "addressLine1" id = "addressLine1" required>

        
        <label for = "addressLine1"><b>Address Line 2: </b></label>
        <input type = "text" placeholder = "Enter Address Line 2" name = "addressLine2" id = "addressLine2">

        
        <label for = "city"><b>City:  </b></label>
        <input type = "text" placeholder = "Enter city" name = "city" id = "city">

        
        <label for = "mobile"><b>Mobile: </b></label>
        <input type = "text" placeholder = "Enter mobile" name = "mobile" id = "mobile" required>

        <label for = "password"><b>Password</b></label>
        <input type = "password" placeholder = "Enter Password" name = "password" id = "password" required>
        <label for = "password_confirmation">Confirm Password: </label>
        <input type = "password" id = "password_confirmation" name = "password_confirmation" length = "6" required>
    

    <button type = "submit" class = "registerbtn">Register</button>

    <div class="signinContainer">
    <p>Already have an account? <a href="loginForm.php">Login</a>.</p>
  </div>

    </div>
</form>
</section>

</body>
</html>