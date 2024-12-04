<?php
include 'database.php'; 

// Path to the categories.json file
$jsonFile = 'categories.json'; // Update this path if needed

// Check if the JSON file exists
if (!file_exists($jsonFile)) {
    die("Error: categories.json file not found.");
}

// Read the JSON file
$jsonData = file_get_contents($jsonFile);

// Decode the JSON data into an associative array
$decodedCategories = json_decode($jsonData, true);

// Check if decoding was successful
if ($decodedCategories === null) {
    die("Error decoding JSON data.");
}

// Insert categories into the database
foreach ($decodedCategories as $category) {
    // Extract the `id` and `description` fields
    $categoryID = isset($category['id']) ? $category['id'] : null;
    $categoryDescription = isset($category['description']) ? $category['description'] : '';

    if ($categoryDescription === '') {
        echo "Skipping invalid category: " . json_encode($category) . "<br>";
        continue;
    }

    // Check if the category already exists by Description
    $stmt = $conn->prepare("SELECT ID FROM category WHERE Description = ?");
    $stmt->bind_param("s", $categoryDescription);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // If the category does not exist, insert it
        $insertCategory = $conn->prepare("INSERT INTO category (Description) VALUES (?)");
        $insertCategory->bind_param("s", $categoryDescription);

        if ($insertCategory->execute()) {
            echo "Inserted category: $categoryDescription<br>";
        } else {
            echo "Error inserting category: $categoryDescription<br>";
        }
        $insertCategory->close();
    } else {
        echo "Category already exists: $categoryDescription<br>";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
