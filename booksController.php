<?php
include 'database.php';

$jsonFile = 'booksNew.json';

if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $books = json_decode($jsonData, true);

    if ($books === null) {
        die("Error decoding JSON data.");
    }

    $stmt = $conn->prepare("INSERT INTO books (ISBN, BookTitle, Author, Edition, Year, CategoryID, URL) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $isbn, $title, $author, $edition, $year, $categoryID, $imageUrl);

    foreach ($books as $book) {
        $isbn = $book['isbn'] ?? '';
        $title = $book['title'] ?? '';
        $author = $book['author'] ?? '';
        $edition = $book['edition'] ?? '';
        $year = $book['year'] ?? '';
        $categoryDescription = $book['category'] ?? '';

        $categoryQuery = $conn->prepare("SELECT ID FROM category WHERE Description = ?");
        $categoryQuery->bind_param("s", $categoryDescription);
        $categoryQuery->execute();
        $categoryQuery->bind_result($categoryID);
        $categoryQuery->fetch();

        if (!$categoryID) {
            $categoryID = 1; // Default category ID
        }

        if (!$stmt->execute()) {
            echo "Error inserting book with ISBN: $isbn<br>";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "JSON file not found.";
}
?>
