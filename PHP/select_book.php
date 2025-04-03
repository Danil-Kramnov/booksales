<!-- select_book.php -->
<?php
include_once 'db_connect.php';
// taken from here: https://stackoverflow.com/questions/50705889/what-does-this-serverrequest-method-post-do
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cbook_title'])) {
  try {
    // Database connection
    $pdo = db_connect();
    
    // Prepare and execute the count query
    $sql = 'SELECT COUNT(book_title) FROM books WHERE book_title LIKE :cbook_title';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cbook_title', '%' . $_POST['cbook_title'] . '%');
    $stmt->execute();
    
    if ($stmt->fetchColumn() > 0) {
      // Prepare and execute the select query
      $sql = 'SELECT book_title FROM books WHERE book_title LIKE :cbook_title';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':cbook_title', '%' . $_POST['cbook_title'] . '%');
      $stmt->execute();
      
      // source 1: https://stackoverflow.com/questions/17765677/login-email-password-match-w-php-and-mysql
      // source 2: https://stackoverflow.com/questions/16846531/how-to-read-fetchpdofetch-assoc
      echo '<h2>Search Results:</h2>';
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<p>' . htmlspecialchars($row['book_title']) . '</p>';
      }
    } else {
      echo '<p>No books found matching your search.</p>';
    }
  } catch (PDOException $e) {
    echo 'Error: ' . htmlspecialchars($e->getMessage());
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  echo '<p>Please enter a book title to search.</p>';
}
?>
