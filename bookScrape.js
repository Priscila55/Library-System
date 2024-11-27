const fs = require('fs'); // Import the file system module
const fetch = require('node-fetch'); // Import the fetch API

// Function to fetch books from Google Books API
async function fetchBooks(
  apiKey,
  subjects = [
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
    "cooking",
  ],
  numBooks = 800
) {
  const bookDetails = [];
  const seenISBNs = new Set();
  const maxResults = 40; // Maximum results per request (limit of Google Books API)

  // Loop through each subject and fetch books
  for (const subject of subjects) {
    let startIndex = 0; // Pagination index for each subject

    while (bookDetails.length < numBooks) {
      const response = await fetch(
        `https://www.googleapis.com/books/v1/volumes?q=subject:${subject}&startIndex=${startIndex}&maxResults=${maxResults}&key=${apiKey}`
      );

      const data = await response.json();

      if (data.items) {
        // Loop through each item in the API response
        for (const item of data.items) {
          const isbn13 = item.volumeInfo.industryIdentifiers?.find(
            (identifier) => identifier.type === "ISBN_13"
          );

          // Ensure the ISBN is unique and not already processed
          if (isbn13 && !seenISBNs.has(isbn13.identifier)) {
            seenISBNs.add(isbn13.identifier); // Mark ISBN as seen

            // Extract image URL if available
            const imageUrl = item.volumeInfo.imageLinks?.thumbnail || ''; 

            // Push the book details, including image URL, into the bookDetails array
            bookDetails.push({
              isbn: isbn13.identifier,
              author: item.volumeInfo.authors?.[0] || "Unknown Author",
              title: item.volumeInfo.title || "Unknown Title",
              year: item.volumeInfo.publishedDate?.split("-")[0] || "Unknown Year",
              category: item.volumeInfo.categories?.[0] || "Unknown Category",
              edition: Math.floor(Math.random() * 7) + 1,
              imageURL: imageUrl, // Add the image URL here
            });
          }
        }
      }

      startIndex += maxResults; // Move to the next batch of books
      if (!data.items || data.items.length === 0) break; // Stop if no more books are found
    }
  }

  // Return the list of book details (with image URLs)
  return bookDetails.slice(0, numBooks);
}

// Example usage with your Google Books API key
const apiKey = "AIzaSyCGKyLRdj1M3sRRGHacQqQAa0af8igR7OI"; // Replace with your API key
fetchBooks(apiKey).then((bookList) => {
  // Save the book details to booksNew.json, including image URLs
  fs.writeFileSync('booksNew.json', JSON.stringify(bookList, null, 2), 'utf-8');
  console.log('Books data with image URLs saved to booksNew.json');
});
