<?php
session_start();
include 'database.php';

// Get filters and pagination parameters
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$booksPerPage = 5;

// Calculate starting record for pagination
$start = ($page - 1) * $booksPerPage;

// Get the search query from the GET parameters (if provided)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// If a search query is provided, modify the query to search for books by title or author
if ($searchQuery) {
    // Modify the SQL query to search for books by title or author
    $sql = "SELECT DISTINCT b.ISBN, b.BookTitle, b.Author, b.Edition, b.Year, c.Description AS CategoryDescription,
                (SELECT COUNT(*) FROM reservations WHERE reservations.ISBN = b.ISBN) AS isReserved
            FROM books b
            LEFT JOIN category c ON b.CategoryID = c.ID
            WHERE b.BookTitle LIKE ? OR b.Author LIKE ?"; // Search by title or author

    // Add parameters for the search query (both title and author)
    $params = ['%' . $searchQuery . '%', '%' . $searchQuery . '%'];
    $types = 'ss'; 
} else {
    $sql = "SELECT DISTINCT b.ISBN, b.BookTitle, b.Author, b.Edition, b.Year, c.Description AS CategoryDescription,
                (SELECT COUNT(*) FROM reservations WHERE reservations.ISBN = b.ISBN) AS isReserved
            FROM books b
            LEFT JOIN category c ON b.CategoryID = c.ID";
    $params = [];
    $types = "";
}

// Pagination logic
$sql .= " LIMIT ?, ?";
$params[] = $start;
$params[] = $booksPerPage;
$types .= "ii";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Total book count for pagination
$countSql = "SELECT COUNT(DISTINCT b.ISBN) AS total 
             FROM books b 
             LEFT JOIN category c ON b.CategoryID = c.ID";
$countParams = [];
$countTypes = "";

if ($categoryFilter) {
    $countSql .= " WHERE c.Description = ?";
    $countParams[] = $categoryFilter;
    $countTypes .= "s";
}

$countStmt = $conn->prepare($countSql);
if ($countTypes) {
    $countStmt->bind_param($countTypes, ...$countParams);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalBooks = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalBooks / $booksPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navBarController.php'; ?>

    <div class="container">
        <h1>Search Results for: <?php echo htmlspecialchars($searchQuery); ?></h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="books-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <h3><?php echo htmlspecialchars($row['BookTitle']); ?></h3>
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($row['Author']); ?></p>
                        <p><strong>Edition:</strong> <?php echo htmlspecialchars($row['Edition']); ?></p>
                        <p><strong>Year:</strong> <?php echo htmlspecialchars($row['Year']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($row['CategoryDescription']); ?></p>

                        <!-- Reserve Button Form -->
                        <form action="reservedBooksController.php" method="POST">
                            <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($row['ISBN']); ?>">
                            <input type="hidden" name="title" value="<?php echo htmlspecialchars($row['BookTitle']); ?>">
                            <?php if ($row['isReserved'] > 0): ?>
                                <button type="submit" name="reserve" class="reserved-btn" disabled>Reserved</button>
                            <?php else: ?>
                                <button type="submit" name="reserve" class="reserve-btn">Reserve</button>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                // Calculate the range of pages to show
                $startPage = max(1, $page - 1);
                $endPage = min($totalPages, $startPage + 2);

                // Adjust start page if near the end
                if ($endPage - $startPage < 2) {
                    $startPage = max(1, $endPage - 2);
                }
                ?>

                <!-- Previous Button -->
                <?php if ($page > 1): ?>
                    <a href="books.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchQuery); ?>">← Previous</a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="books.php?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>" 
                       class="<?php echo $i == $page ? 'current-page' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- Next Button -->
                <?php if ($page < $totalPages): ?>
                    <a href="books.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchQuery); ?>">Next →</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>No books found matching your search.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>

<?php
$stmt->close();
$countStmt->close();
$conn->close();
?>
