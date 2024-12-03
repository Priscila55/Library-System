<?php
session_start();
// Include database connection
include 'database.php';

// Number of books per page (set to 10)
$booksPerPage = 10;

// Get the current page number, default to page 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting record based on the current page
$start = ($page - 1) * $booksPerPage;

// SQL query to get the books for the current page
$sql = "SELECT b.ISBN, b.BookTitle, b.Author, b.Edition, b.Year, c.Description AS CategoryDescription
        FROM books b
        LEFT JOIN category c ON b.CategoryID = c.ID
        LIMIT $start, $booksPerPage";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    // Query failed, display the error
    die("Error executing query: " . $conn->error); // This will show the MySQL error
}

// Now check the number of rows
if ($result->num_rows > 0) {
    // Output data of each row
    echo "<div class='books-container'>";

    while ($row = $result->fetch_assoc()) {
        echo "<div class='book-card' onclick='reserveBook(\"" . $row["ISBN"] . "\", \"" . $row["BookTitle"] . "\")'>";

        // Display book details (no images)
        echo "<div class='book-details'>";
        echo "<h3>" . $row["BookTitle"] . "</h3>";  // Book title
        echo "<p><strong>Author:</strong> " . $row["Author"] . "</p>"; // Author
        echo "<p><strong>Edition:</strong> " . $row["Edition"] . "</p>"; // Edition
        echo "<p><strong>Year:</strong> " . $row["Year"] . "</p>"; // Year
        echo "<p><strong>Category:</strong> " . $row["CategoryDescription"] . "</p>"; // Category
        echo "</div>";  // End book details

         // Reserve Button with form submission
         echo "<form action='reservedBooksController.php' method='POST'>";
         echo "<input type='hidden' name='isbn' value='" . $row['ISBN'] . "'>";
         echo "<input type='hidden' name='title' value='" . $row['BookTitle'] . "'>";
         echo "<button type='submit' class='reserve-btn'>Reserve</button>";
         echo "</form>";
     
        echo "</div>";  // End book card
    }

    echo "</div>";  // End books container

    // Pagination: Calculate total number of pages
    $countSql = "SELECT COUNT(*) AS total FROM books";
    $countResult = $conn->query($countSql);

    // Check if the count query was successful
    if ($countResult === false) {
        die("Error executing count query: " . $conn->error);
    }

    $row = $countResult->fetch_assoc();
    $totalBooks = $row['total'];
    $totalPages = ceil($totalBooks / $booksPerPage);

    // Display pagination links with a limited range of page numbers
    $range = 2; // Display 2 pages before and after the current page
    echo "<div class='pagination'>";

    // Previous range
    if ($page > 1) {
        echo "<a href='books.php?page=" . ($page - 1) . "'>Previous</a>";
    }

    // Loop through a limited page range
    for ($i = max(1, $page - $range); $i <= min($totalPages, $page + $range); $i++) {
        if ($i == $page) {
            echo "<span class='current-page'>$i</span>";  // Highlight the current page
        } else {
            echo "<a href='books.php?page=$i'>$i</a>";  // Page link
        }
    }

    // Next range
    if ($page < $totalPages) {
        echo "<a href='books.php?page=" . ($page + 1) . "'>Next</a>";
    }

    echo "</div>";  // End pagination div

} else {
    echo "No books found.";
}

// Close the connection
$conn->close();
?>
