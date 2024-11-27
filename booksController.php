<?php
// Database connection details
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

// Path to the booksNew.json file
$jsonFile = 'booksNew.json';  // Update this path if needed

// Check if the JSON file exists
if (file_exists($jsonFile)) {
    // Read the JSON file
    $jsonData = file_get_contents($jsonFile);

    // Decode the JSON data into an associative array
    $books = json_decode($jsonData, true);

    // Check if the decoding was successful
    if ($books === null) {
        die("Error decoding JSON data.");
    }

    // Prepare SQL statement for inserting data into the books table
    $stmt = $conn->prepare("INSERT INTO books (ISBN, BookTitle, Author, Edition, Year, CategoryID, URL) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Check if the prepare statement was successful
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    // Bind parameters for the prepared statement
    $stmt->bind_param("sssssis", $isbn, $title, $author, $edition, $year, $categoryID, $imageUrl);

    // Iterate through each book and insert it into the database
    foreach ($books as $book) {
        // Map the keys from the JSON file to the database column names
        $isbn = isset($book['isbn']) ? $book['isbn'] : '';
        $title = isset($book['title']) ? $book['title'] : '';
        $author = isset($book['author']) ? $book['author'] : '';
        $edition = isset($book['edition']) ? $book['edition'] : '';
        $year = isset($book['year']) ? $book['year'] : '';
        $categoryDescription = isset($book['category']) ? $book['category'] : '';  // Assuming 'category' field exists in JSON

        // Get the CategoryID based on the category name
        $categoryQuery = $conn->prepare("SELECT ID FROM category WHERE Description = ?");
        $categoryQuery->bind_param("s", $categoryDescription);
        $categoryQuery->execute();
        $categoryQuery->bind_result($categoryID);
        $categoryQuery->fetch();

        // If category doesn't exist, set a default (e.g., 'Unknown')
        if (!$categoryID) {
            $categoryID = 1;  // Assuming '1' is the default or unknown category ID
        }

        // Get the Image URL from the JSON data (this will be stored in the 'URL' column)
        $imageUrl = isset($book['imageUrl']) ? $book['imageUrl'] : '';  // Use the 'imageUrl' field from JSON

        // Execute the statement to insert the book into the books table
        if (!$stmt->execute()) {
            echo "Error inserting book with ISBN: $isbn<br>";
        } else {
            echo "Inserted book with ISBN: $isbn<br>";
        }
    }

    // Close the prepared statement
    $stmt->close();
    echo "Books data inserted successfully.<br>";
} else {
    echo "Error: booksNew.json file not found.<br>";
}

// Close the database connection
$conn->close();
?>
