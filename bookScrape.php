<?php 

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'library_system';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " .$conn->connect_error);
}

$ch = curl_init(); 

curl_setopt($ch, CURLOPT_URL, "https://books.toscrape.com/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);

// close curl session
curl_close($ch); 

// check if the page was fetched 
if (!$html) {
    die("Error fetching page content");
}

preg_match_all('/<article class="product_pod">.*?<h3><a href="(.*?)" title="(.*?)">.*?<p class="price_color">(.*?)<\/p>/s', $html, $matches);

// check if there are matches 
if (count($matches[0]) > 0) {
    for ($i = 0; $i < count($matches[0]); $i++) {
        // Extracted data
        $url = "https://books.toscrape.com" . $matches[1][$i];
        $bookTitle = $matches[2][$i];
        $price = $matches[3][$i];

        // placeholder values for book details 
        $isbn = "N/A";
        $author = "N/A";
        $edition = "N/A";
        $year = "N/A"; 
        $categoryID = 1;

        $bookPageHtml = file_get_html($url);
        if ($bookPageHtml) {
        $isbn = $bookPageHtml->find('th', 0)->plaintext; // Example to extract ISBN
        $author = $bookPageHtml->find('a[href*="author"]', 0)->plaintext; // Example for Author
    // Add more extraction logic here
        }

        // Insert the book into the database
        $stmt = $conn->prepare("INSERT INTO books (ISBN, BookTitle, Author, Edition, Year, CategoryID, Price, URL) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiis", $isbn, $bookTitle, $author, $edition, $year, $categoryID, $price, $url);
        
        if (!$stmt->execute()) {
            echo "Error inserting book: " .$stmt->error . "\n";
        }

    }

}
