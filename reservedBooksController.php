<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: loginForm.php");
    exit();
}

include 'database.php'; // Include your database connection

$username = $_SESSION['user']; // Username stored in session

// Fetch the userID based on the logged-in username
$sqlUserID = "SELECT userID FROM users WHERE Username = ?";
$stmtUserID = $conn->prepare($sqlUserID);
$stmtUserID->bind_param("s", $username);
$stmtUserID->execute();
$resultUserID = $stmtUserID->get_result();

if ($resultUserID->num_rows > 0) {
    $row = $resultUserID->fetch_assoc();
    $userID = $row['userID']; // Get userID
} else {
    echo "Error: User not found.";
    exit();
}

// Check if the book is already reserved
if (isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];
    $currentDate = date('Y-m-d');

    // Check if the book is already reserved by another user
    $sqlCheckReservation = "SELECT * FROM reservations WHERE ISBN = ?";
    $stmtCheckReservation = $conn->prepare($sqlCheckReservation);
    $stmtCheckReservation->bind_param("s", $isbn);
    $stmtCheckReservation->execute();
    $resultCheck = $stmtCheckReservation->get_result();

    if ($resultCheck->num_rows > 0) {
        // Book is already reserved, show a message
        echo "<p>The book with ISBN: $isbn is already reserved by another user.</p>";
    } else {
        // Book is not reserved, proceed with the reservation
        $sqlReserveBook = "INSERT INTO reservations (userID, ISBN, ReservedDate) VALUES (?, ?, ?)";
        $stmtReserveBook = $conn->prepare($sqlReserveBook);
        $stmtReserveBook->bind_param("iss", $userID, $isbn, $currentDate);

        if ($stmtReserveBook->execute()) {
            // Reservation successful
            echo "<p>The book has been successfully reserved!</p>";
            // Redirect to the reservations page or main page
            header("Location: myReservations.php");
            exit();
        } else {
            // Error while reserving the book
            echo "Error reserving book: " . $stmtReserveBook->error;
        }
    }

    // Close the statement for reservation checking
    $stmtCheckReservation->close();

    // Check if the reservation statement was created before calling close
    if (isset($stmtReserveBook)) {
        $stmtReserveBook->close();
    }
} else {
    echo "Invalid request.";
}

// Close the statement for user lookup
$stmtUserID->close();
?>
