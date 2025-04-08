<!-- index.php -->
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

  </nav>

  <nav class="genre-bar">
    <ul>
      <li><a href="index.php">Home</a></li> <!-- home page -->
      <li><a href="">Help</a></li> <!-- wiki page -->
      <li><a href="PHP/accounts_admin.php">Account</a></li> <!-- drop down menu on hover: Update details, Your Orders (data table ordered_books)  -->
      <li><a href="PHP/books_admin.php">Books Admin</a></li>
      <li><a href="">Basket</a></li> <!-- items that saved during user session -->
    </ul>
  </nav>

  <main>

  <h1> WELCOME TO HOME PAGE </h1>

  </main>

  <footer>
    <a href="#">Link</a>
    &copy; 2025 BookStore
  </footer>

</body>
</html>
