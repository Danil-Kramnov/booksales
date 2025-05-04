<?php
// delete_book.php

if (isset($_POST['show_delete_confirm']) && isset($_POST['cid'])) {
  try {
    $pdo = db_connect();
    $book_id = $_POST['cid'];
    
    $sql = 'SELECT * FROM books WHERE book_id = :cid';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cid', $book_id);
    $stmt->execute();
    $book = $stmt->fetch();
    
    if ($book) {
      $_SESSION['book_to_delete'] = $book_id;
      $_SESSION['delete_book_details'] = $book;
      //https://www.php.net/manual/en/function.header.php
      header('Location: books_admin.php');
      exit();
    } else {
      $_SESSION['error_message'] = "No book found with that ID.";
      //https://www.php.net/manual/en/function.header.php
      header('Location: books_admin.php');
      exit();
    }
  } catch (PDOException $e) {
    $_SESSION['error_message'] = "Error fetching book: " . $e->getMessage();
    //https://www.php.net/manual/en/function.header.php
    header('Location: books_admin.php');
    exit();
  }
}

if (isset($_POST['confirm_delete']) && isset($_SESSION['book_to_delete'])) {
  try {
    $book_id = $_SESSION['book_to_delete'];
    
    $pdo = db_connect();
    $sql = 'DELETE FROM books WHERE book_id = :cid';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cid', $book_id);
    $stmt->execute();
    
    $_SESSION['success_message'] = "Book deleted successfully!";
    
    unset($_SESSION['book_to_delete']);
    unset($_SESSION['delete_book_details']);
    //https://www.php.net/manual/en/function.header.php
    header('Location: books_admin.php');
    exit();
  } catch (PDOException $e) {
    $_SESSION['error_message'] = "Error deleting book: " . $e->getMessage();
    //https://www.php.net/manual/en/function.header.php
    header('Location: books_admin.php');
    exit();
  }
}

if (isset($_POST['cancel_delete'])) {
  unset($_SESSION['book_to_delete']);
  unset($_SESSION['delete_book_details']);
  header('Location: books_admin.php');
  exit();
}

if (isset($_SESSION['book_to_delete']) && isset($_SESSION['delete_book_details'])) {
  $book = $_SESSION['delete_book_details'];
  ?>
  <div class="delete-confirmation">
    <h2>Delete Book</h2>
    <p>Are you sure you want to delete the book: <strong><?php echo htmlspecialchars($book['book_title']); ?></strong> by <?php echo htmlspecialchars($book['author']); ?>?</p>
    <div class="button-group">
      <form action="books_admin.php" method="POST" style="display:inline-block;">
        <input type="hidden" name="cid" value="<?php echo htmlspecialchars($_SESSION['book_to_delete']); ?>">
        <input type="hidden" name="confirm_delete" value="yes">
        <button type="submit" name="delete_book">Yes, Delete</button>
      </form>
      
      <form action="books_admin.php" method="POST" style="display:inline-block;">
        <button type="submit" name="cancel_delete">No, Cancel</button>
      </form>
    </div>
  </div>
  <?php
}
?>