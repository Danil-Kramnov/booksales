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

  <nav>
    <div class="logo">BookStore</div>
  </nav>

  <nav class="genre-bar">

    <ul>
      <li><a href="../index.php">Home</a></li> <!-- home page -->
      <li><a href="">Help</a></li> <!-- wiki page -->
      <li><a href="accounts_admin.php">Account</a></li> <!-- drop down menu on hover: Update details, Your Orders (data table ordered_books)  -->
      <li><a href="books_admin.php">Books Admin</a></li>
      <li><a href="">Basket</a></li> <!-- items that saved during user session -->
    </ul>

  </nav>

  <main>
    <?php include 'select_book.php'; ?>
    <?php include '../HTML/add_book_form.html'; ?>
    <?php //include '../HTML/update_book_form.html'; ?>

    <?php
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
          echo "You did not complete the insert form correctly.<br>";
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

          echo "Book added successfully! Try adding another.<br>";
        }

      } catch (PDOException $e) {
        $title  = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() 
                  . ' in ' . $e->getFile() . ':' . $e->getLine();
        echo $output;
      }
    }
    ?>

    <?php 
      include 'delete_book.php'; 
    ?>

    <?php 
      include 'update_book.php'; 
    ?>

    <?php
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

        echo "    <form action='update_book.php' method='POST' style='display:inline-block;'>";
        echo "      <input type='hidden' name='cid' value='" . htmlspecialchars($row['book_id']) . "'>";
        echo "      <button type='submit'>Update</button>";
        echo "    </form>";

        echo "    <form action='delete_book.php' method='POST' style='display:inline-block;'>";
        echo "      <input type='hidden' name='cid' value='" . htmlspecialchars($row['book_id']) . "'>";
        echo "      <input type='hidden' name='delete_book' value='1'>";

        echo "      <button type='submit'>Delete</button>";

        echo "    </form>";

        

        echo "  </td>";
        echo "</tr>";
      }

      echo "  </tbody>";
      echo "</table>";

    } catch (PDOException $e) {
      echo "Error fetching books: " . $e->getMessage();
    }
    ?>
  </main>

  <footer>
    <a href="#">Link</a>
    &copy; 2025 BookStore
  </footer>

</body>
</html>
