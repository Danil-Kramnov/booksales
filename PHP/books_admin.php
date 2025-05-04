<!-- books_admin.php -->
<?php
session_start(); 
require_once 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Bookstore</title>
  <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>

  <nav class="main-nav">
    
    <?php include 'HTML/header.html'; ?>
    <?php include 'HTML/navigation.html'; ?>

  </nav>

  <main>
    
    <?php
    // Display success/error messages
    if (isset($_SESSION['success_message'])) {
      echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
      unset($_SESSION['success_message']);
    }
    
    if (isset($_SESSION['error_message'])) {
      echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
      unset($_SESSION['error_message']);
    }
    

    include 'update_book.php';
    include 'delete_book.php';
    
    // Show the add book form if we are not updating or deleting
    if (!isset($_SESSION['book_to_update']) && !isset($_SESSION['book_to_delete'])) {
      include '../HTML/add_book_form.html';
      
      // Process add book form
      if (isset($_POST['add_book'])) {
        try {
          $cbook_title = $_POST['cbook_title'];
          $cauthor = $_POST['cauthor'];
          $cisbn = $_POST['cisbn'];
          $cgenre_code = $_POST['cgenre_code'];
          $cprice = $_POST['cprice'];
          $cstock_amount = $_POST['cstock_amount'];
          $cbook_status = $_POST['cbook_status'];

          if ($cbook_title=='' or $cauthor=='' or $cisbn=='' or $cgenre_code=='' or $cprice=='' or $cstock_amount=='' or $cbook_status=='') {
            echo "<p class='error-message'>You did not complete the insert form correctly.</p>";
          } else {
            $pdo = db_connect();

            $sql = "INSERT INTO books (book_title, author, ISBN , genre_code, price, stock_amount, book_status) VALUES(:cbook_title, :cauthor, :cisbn, :cgenre_code, :cprice, :cstock_amount, :cbook_status)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cbook_title', $cbook_title);
            $stmt->bindValue(':cauthor', $cauthor);
            $stmt->bindValue(':cisbn', $cisbn);
            $stmt->bindValue(':cgenre_code', $cgenre_code);
            $stmt->bindValue(':cprice', $cprice);
            $stmt->bindValue(':cstock_amount', $cstock_amount);
            $stmt->bindValue(':cbook_status', $cbook_status);
            $stmt->execute();

            echo "<p class='success-message'>Book added successfully! Try adding another.</p>";
          }
        } catch (PDOException $e) {
          $title  = 'An error has occurred';
          $output = 'Database error: ' . $e->getMessage() 
                    . ' in ' . $e->getFile() . ':' . $e->getLine();
          echo "<p class='error-message'>" . $output . "</p>";
        }
      }
    }
    ?>

    <?php
    // Display the books table
    try {
      if (!isset($pdo)) {
        $pdo = db_connect();
      }

      $stmt = $pdo->query("SELECT * FROM books ORDER BY book_title ASC");

      echo "<h2>Available Books</h2>";
      echo "<table class='books-table'>";
      echo "  <thead>";
      echo "    <tr>";
      echo "      <th>Title</th>";
      echo "      <th>Author</th>";
      echo "      <th>Price</th>";
      echo "      <th>Stock</th>";
      echo "      <th>Action</th>";
      echo "    </tr>";
      echo "  </thead>";
      echo "  <tbody>";

      while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "  <td>" . htmlspecialchars($row['book_title']) . "</td>";
        echo "  <td>" . htmlspecialchars($row['author']) . "</td>";
        echo "  <td>" . htmlspecialchars($row['price']) . "</td>";
        echo "  <td>" . htmlspecialchars($row['stock_amount']) . "</td>";

        echo "  <td>";

        echo "    <form action='books_admin.php' method='POST' style='display:inline-block;'>";
        echo "      <input type='hidden' name='cid' value='" . htmlspecialchars($row['book_id']) . "'>";
        echo "      <input type='hidden' name='show_update_form' value='1'>";
        echo "      <button type='submit'>Update</button>";
        echo "    </form>";

        echo "    <form action='books_admin.php' method='POST' style='display:inline-block;'>";
        echo "      <input type='hidden' name='cid' value='" . htmlspecialchars($row['book_id']) . "'>";
        echo "      <input type='hidden' name='show_delete_confirm' value='1'>";
        echo "      <button type='submit'>Delete</button>";
        echo "    </form>";

        echo "  </td>";
        echo "</tr>";
      }

      echo "  </tbody>";
      echo "</table>";

    } catch (PDOException $e) {
      echo "<p class='error-message'>Error fetching books: " . $e->getMessage() . "</p>";
    }
    ?>

  </main>

  <footer>
    <a href="#">Link</a>
    &copy; 2025 BookStore
  </footer>

</body>
</html>