<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel = "stylesheet" href = "styles.css">
</head>
<body>
<?php include 'navBarController.php' ;?>

<div class = "container">
    <h1>Books List</h1>

    <?php
    include('displayBooksController.php');
    ?>
</div>
</body>
</html>