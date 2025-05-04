<?php
session_start();
require_once 'PHP/db_connect.php';
require_once 'PHP/basket_functions.php';

$pdo = db_connect();

$sql = "SELECT book_id, book_title, author, price FROM books WHERE book_status = 'A'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$itemCount = getBasketItemCount();
$subtotal = calculateBasketSubtotal();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Bookstore</title>
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <nav class="main-nav">
        <?php include 'HTML/header.html'; ?>
        <?php include 'HTML/navigation.html'; ?>
        <?php if ($itemCount > 0): ?>
        <div class="basket-summary">
            <div class="basket-subtotal">
                Basket Subtotal: <span class="price">€<?php echo number_format($subtotal, 2); ?></span>
            </div>
            <div class="basket-actions">
                <a href="PHP/basket.php" class="view-basket-btn">Go to basket</a>
            </div>
        </div>
        <?php endif; ?>
    </nav>
    <main>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="message">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        
        <div class="book-gallery">
            <?php
            $books = $stmt->fetchAll();
            if (count($books) > 0) {
                foreach ($books as $row) {
                    $book_id = $row['book_id'];
                    $book_title = htmlspecialchars($row['book_title']);
                    $author = htmlspecialchars($row['author']);
                    $price = $row['price'];
                    
                    echo "<div class='book-card'>";
                    echo "<img src='IMG/{$book_title}.png' alt='{$book_title}' class='book-image' \">";
                    echo "<div class='book-title'>{$book_title}</div>";
                    echo "<div class='book-author'>by {$author}</div>";
                    echo "<div class='book-price'>€{$price}</div>";
                    echo "<form action='PHP/add_to_basket.php' method='POST'>";
                    echo "<input type='hidden' name='book_id' value='{$book_id}'>";
                    echo "<button type='submit' class='add-to-basket'>Add to Basket</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No books available at the moment.</p>";
            }
            ?>
        </div>
    </main>
    <?php include 'HTML/footer.html'; ?>
</body>
</html>