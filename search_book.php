<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for a book</title>
    <link rel = "stylesheet" href = "styles.css">
</head>
<body>
<?php include 'navBarController.php' ;?>

<h2> Search for a book</h2>
<from action = "search_results.php" method = "GET">
    <label for = "search_term">Search by Title, Author or Category: </label>
    <input type = "text" id = "search_term" name = "search_term" placeholder = "Enter a book title or author" required>

    <button type = "submit">Search</button>

</from>
</body>
</html>