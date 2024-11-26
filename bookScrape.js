const fs = require('fs'); // Import the file system module

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
 
  for (const subject of subjects) {
    let startIndex = 0; // Pagination index for each subject
 
    while (bookDetails.length < numBooks) {
      const response = await fetch(
        `https://www.googleapis.com/books/v1/volumes?q=subject:${subject}&startIndex=${startIndex}&maxResults=${maxResults}&key=${apiKey}`
      );
 
      const data = await response.json();
 
      if (data.items) {
        for (const item of data.items) {
          const isbn13 = item.volumeInfo.industryIdentifiers?.find(
            (identifier) => identifier.type === "ISBN_13"
          );
 
          if (isbn13 && !seenISBNs.has(isbn13.identifier)) {
            seenISBNs.add(isbn13.identifier); // Prevent duplicates
            bookDetails.push({
              isbn: isbn13.identifier,
              author: item.volumeInfo.authors?.[0] || "Unknown Author",
              title: item.volumeInfo.title || "Unknown Title",
              year:
                item.volumeInfo.publishedDate?.split("-")[0] || "Unknown Year",
              category: item.volumeInfo.categories?.[0] || "Unknown Category",
              edition: Math.floor(Math.random() * 7) + 1,
            });
          }
        }
      }
 
      startIndex += maxResults; // Move to the next batch
      if (!data.items || data.items.length === 0) break; // Stop if no more books are found
    }
  }
 
  fs.writeFileSync('books.json', JSON.stringify(bookDetails, null, 2), 'utf-8');
  console.log('Books data saved to books.json');

  // return the data
  return bookDetails;
}
 
// Example usage
const apiKey = "AIzaSyCGKyLRdj1M3sRRGHacQqQAa0af8igR7OI"; // Replace with your API key
fetchBooks(apiKey).then((bookList) => console.log(bookList));
 
