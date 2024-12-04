<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: loginForm.php"); // Redirect to login if not logged in
    exit();
}

include 'database.php'; // Include the database connection

$username = $_SESSION['user']; // Get the logged-in username

// Fetch the userID based on the logged-in username
$sqlUserID = "SELECT userID FROM users WHERE Username = ?";
$stmtUserID = $conn->prepare($sqlUserID);
$stmtUserID->bind_param("s", $username);
$stmtUserID->execute();
$resultUserID = $stmtUserID->get_result();

if ($resultUserID->num_rows > 0) {
    $row = $resultUserID->fetch_assoc();
    $userID = $row['userID']; // Get the userID for the logged-in user
} else {
    echo "Error: User not found.";
    exit();
}

// Handle Unreserve Action
if (isset($_POST['unreserve'])) {
    $isbnToRemove = $_POST['isbn'];

    // Delete the reservation from the reservations table
    $sqlUnreserve = "DELETE FROM reservations WHERE userID = ? AND ISBN = ?";
    $stmtUnreserve = $conn->prepare($sqlUnreserve);
    $stmtUnreserve->bind_param("is", $userID, $isbnToRemove);

    if ($stmtUnreserve->execute()) {
        // Successfully unreserved
    } else {
        echo "Error unreserving book: " . $stmtUnreserve->error;
    }

    $stmtUnreserve->close();
}

// Fetch reservations for the logged-in user
$sqlReservations = "SELECT * FROM reservations WHERE userID = ?";
$stmtReservations = $conn->prepare($sqlReservations);
$stmtReservations->bind_param("i", $userID); // Bind userID as an integer
$stmtReservations->execute();
$resultReservations = $stmtReservations->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external CSS -->
</head>
<body>

<?php include 'navBarController.php'; ?> <!-- Include your navigation bar -->

<h1>My Reservations</h1>

<div class="reservations_container">
    <?php
    if ($resultReservations->num_rows > 0) {
        echo "<ul>";

        // Display each reserved book
        while ($reservation = $resultReservations->fetch_assoc()) {
            // Add a class 'reserved-book' to the list item to style it
            echo "<li class='reserved-book'>";
            echo "ISBN: " . htmlspecialchars($reservation['ISBN']) . " (Reserved on: " . htmlspecialchars($reservation['ReservedDate']) . ")";

            // Display the "Unreserve" button for each book
            echo "<form method='POST' action='myReservations.php'>
                    <input type='hidden' name='isbn' value='" . $reservation['ISBN'] . "'>
                    <input type='submit' name='unreserve' value='Unreserve' class='unreserve-btn'>
                  </form>";
            echo "</li>";
        }

        echo "</ul>";
    } else {
        echo "<p>No reservations yet.</p>";
    }

    // Close the statement
    $stmtReservations->close();
    $stmtUserID->close();
    ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
