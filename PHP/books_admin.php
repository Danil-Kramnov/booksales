<!-- books_admin.php -->
<?php 
require_once 'PHP/db_connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Bookstore</title>
  <link rel="stylesheet" href="CSS/index.css">
</head>
<body>

  <nav>
    <div class="logo">BookStore</div>

    <form action="index.php" method="POST" class="search-bar">
      <input type="text" name="cbook_title" placeholder="Search for books...">
      <button type="submit">Search</button>
    </form>

    <div class="nav-buttons">
      <button href="">Help</button> <!-- just a wiki page with description of what you can do using this system  -->
      <button href="accounts_admin.php">Account</button> <!-- links on hover: Update details, Your Orders (data table ordered_books)  -->
      <button href="PHP/books_admin.php">Books Admin</button> <!-- heres one page with table of books. In table Action buttons: "Update", "Delete"  -->
      <button href="">Basket</button> <!-- items that saved during user session -->
    </div>
  </nav>

  <nav class="genre-bar">
    <ul>

      <li><a href="">Detective</a></li>
      <li><a href="">Sci-fi</a></li>
      <li><a href="">History</a></li>
      <li><a href="">Fantasy</a></li>
      <li><a href="">Romance</a></li>
      <li><a href="">Poems</a></li>
      <li><a href="">Biography</a></li>
      <li><a href="">Non-fiction</a></li>
      <li><a href="">Fiction</a></li>
    </ul>
  </nav>

  <main>
    <?php include 'PHP/select_book.php'; ?>
    <?php include 'HTML/add_book_form.html'; ?>
    <?php include 'HTML/update_book_form.html'; ?>

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
      include 'PHP/delete_book.php'; 
    ?>

    <?php 
      include 'PHP/update_book.php'; 
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

        echo "    <form action='PHP/delete_book.php' method='POST' style='display:inline-block;'>";
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
