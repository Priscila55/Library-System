<?php
// db_connect.php - Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected to the database successfully.<br>";
}

// Define your categories
$categories = [
    "fiction",
    "science",
    "history",
    "philosophy",
    "biography",
    "art",
    "computers",
    "mathematics",
    "medicine",
    "technology",
    "education",
    "psychology",
    "sports",
    "travel",
    "cooking"
];

// Insert categories into the database if they don't already exist
foreach ($categories as $category) {
    // Check if the category already exists
    $stmt = $conn->prepare("SELECT CategoryID FROM category WHERE CategoryDescription = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // If the category does not exist, insert it
        $insertCategory = $conn->prepare("INSERT INTO category (CategoryDescription) VALUES (?)");
        $insertCategory->bind_param("s", $category);

        if ($insertCategory->execute()) {
            echo "Inserted category: $category<br>";
        } else {
            echo "Error inserting category: $category<br>";
        }
    } else {
        echo "Category already exists: $category<br>";
    }
}

$conn->close();
?>
