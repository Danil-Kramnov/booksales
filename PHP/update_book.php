<?php
// update_book.php

if (isset($_POST['update_book']) && isset($_SESSION['book_to_update'])) {
  try {
    $book_id = $_SESSION['book_to_update'];
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $genre_code = $_POST['genre_code'];
    $price = $_POST['price'];
    $stock_amount = $_POST['stock_amount'];
    $book_status = $_POST['book_status'];
    
    $pdo = db_connect();
    $sql = "UPDATE books SET book_title = :book_title, author = :author, ISBN = :isbn, 
            genre_code = :genre_code, price = :price, stock_amount = :stock_amount, 
            book_status = :book_status WHERE book_id = :book_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':book_title', $book_title);
    $stmt->bindValue(':author', $author);
    $stmt->bindValue(':isbn', $isbn);
    $stmt->bindValue(':genre_code', $genre_code);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':stock_amount', $stock_amount);
    $stmt->bindValue(':book_status', $book_status);
    $stmt->bindValue(':book_id', $book_id);
    $stmt->execute();
    
    $_SESSION['success_message'] = "Book updated successfully!";
    
    unset($_SESSION['book_to_update']);
    unset($_SESSION['book_details']);
    //https://www.php.net/manual/en/function.header.php
    header('Location: books_admin.php');
    exit();
  } catch (PDOException $e) {
    $_SESSION['error_message'] = "Error updating book: " . $e->getMessage();
    //https://www.php.net/manual/en/function.header.php
    header('Location: books_admin.php');
    exit();
  }
}

if (isset($_POST['show_update_form']) && isset($_POST['cid'])) {
  try {
    $pdo = db_connect();
    $book_id = $_POST['cid'];
    
    $sql = 'SELECT * FROM books WHERE book_id = :cid';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cid', $book_id);
    $stmt->execute();
    $book = $stmt->fetch();
    
    if ($book) {
      $_SESSION['book_to_update'] = $book_id;
      $_SESSION['book_details'] = $book;
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

if (isset($_POST['cancel_update'])) {
  unset($_SESSION['book_to_update']);
  unset($_SESSION['book_details']);
  //https://www.php.net/manual/en/function.header.php
  header('Location: books_admin.php');
  exit();
}

if (isset($_SESSION['book_to_update']) && isset($_SESSION['book_details'])) {
  $book = $_SESSION['book_details'];
  ?>
  <div class="update-form">
    <h2>Update Book</h2>
    <form action="books_admin.php" method="POST">
      <div>
        <label for="book_title">Title:</label>
        <input type="text" id="book_title" name="book_title" value="<?php echo htmlspecialchars($book['book_title']); ?>" required>
      </div>
      <div>
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
      </div>
      <div>
        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>" required>
      </div>
      <div>
        <label for="genre_code">Genre Code:</label>
        <input type="text" id="genre_code" name="genre_code" value="<?php echo htmlspecialchars($book['genre_code']); ?>" required>
      </div>
      <div>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($book['price']); ?>" required>
      </div>
      <div>
        <label for="stock_amount">Stock Amount:</label>
        <input type="number" id="stock_amount" name="stock_amount" value="<?php echo htmlspecialchars($book['stock_amount']); ?>" required>
      </div>
      <div>
        <label for="book_status">Book Status:</label>
        <input type="text" id="book_status" name="book_status" value="<?php echo htmlspecialchars($book['book_status']); ?>" required>
      </div>
      <div class="button-group">
        <button type="submit" name="update_book">Update Book</button>
        <button type="submit" name="cancel_update">Cancel</button>
      </div>
    </form>
  </div>
  <?php
}
?>