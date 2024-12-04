<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navBarController.php'; 
    include 'database.php' ?>


    <header>
        <h1>Welcome to Book Reservation</h1>
        <p>Unlock a world of books ready for reservation with just one click</p>
    </header>

    <div class = "search_container">
    <form method="GET" action="displayBooksController.php">
        <div class="dropdown">
            <!-- Search Input -->
            <input 
                type="search" 
                name="search" 
                id="search" 
                placeholder="Search books by title or author" 
                onfocus="showDropdown()" 
                oninput="showDropdown()" 
                autocomplete="off" 
            />

            <!-- Submit Button -->
            <button type="submit" id="button">Search</button>

            <!-- Filter Dropdown -->
            <div class="dropdown-content" id="dropdownContent">
                <a href="books.php?category=fiction">Fiction</a>
                <a href="books.php?category=science">Science</a>
                <a href="books.php?category=history">History</a>
                <a href="books.php?category=philosophy">Philosophy</a>
                <a href="books.php?category=biography">Biography</a>
                <a href="books.php?category=art">Art</a>
                <a href="books.php?category=computers">Computers</a>
                <a href="books.php?category=mathematics">Mathematics</a>
                <a href="books.php?category=medicine">Medicine</a>
                <a href="books.php?category=technology">Technology</a>
                <a href="books.php?category=education">Education</a>
                <a href="books.php?category=psychology">Psychology</a>
                <a href="books.php?category=sports">Sports</a>
                <a href="books.php?category=travel">Travel</a>
                <a href="books.php?category=cooking">Cooking</a>
            </div>
        </div>
     </div>
    </form>

    <div class="random-books-section">
        <h3>Trending Now</h3>
        <div class="random-books-container">
            <ul class="random-books-list">
                <?php
                $jsonFile = 'booksNew.json';
                $randomBooks = [];

                if (file_exists($jsonFile)) {
                    $jsonData = file_get_contents($jsonFile);
                    $booksArray = json_decode($jsonData, true);
                    shuffle($booksArray);
                    $randomBooks = array_slice($booksArray, 0, 5);
                }

                if (!empty($randomBooks)) {
                    foreach ($randomBooks as $book): ?>
                        <li class="random-book-item">
                            <strong><?php echo htmlspecialchars($book['title']); ?></strong><br>
                            <span>by <?php echo htmlspecialchars($book['author']); ?></span>
                        </li>
                    <?php endforeach;
                } else { ?>
                    <p>No trending books available at the moment.</p>
                <?php } ?>
            </ul>
        </div>
    </div>

    <script>
        function showDropdown() {
            const dropdownContent = document.getElementById('dropdownContent');
            dropdownContent.classList.add('show');
        }

        // Close the dropdown if the user clicks outside
        window.addEventListener('click', function (e) {
            const dropdownContent = document.getElementById('dropdownContent');
            const searchInput = document.getElementById('search');

            // Check if the click is outside both the dropdown and the search input
            if (!dropdownContent.contains(e.target) && e.target !== searchInput) {
                dropdownContent.classList.remove('show');
            }
        });
    </script>

<?php include 'footer.php'; ?>
</body>
</html>
