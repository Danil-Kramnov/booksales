<?php
session_start();
require_once 'db_connect.php';
require_once 'basket_functions.php';

if (isset($_POST['remove_item']) && isset($_POST['book_id'])) {
    removeFromBasket($_POST['book_id']);
}

if (isset($_POST['update_qty']) && isset($_POST['book_id']) && isset($_POST['quantity'])) {
    updateBasketQuantity($_POST['book_id'], $_POST['quantity']);
}

$total = calculateBasketSubtotal();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Basket - Online Bookstore</title>
    <link rel="stylesheet" href="../CSS/index.css">
</head><data value="
"></data>
<body>
    <nav class="main-nav">
        <?php include '../HTML/header.html'; ?>
        <?php include '../HTML/navigation.html'; ?>
    </nav>
    
    <main class="basket-container">
        <h1>Your Shopping Basket</h1>
        
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="message">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        
        if (!isset($_SESSION['basket']) || empty($_SESSION['basket'])) {
            echo '<div class="empty-basket">';
            echo '<h2>Your basket is empty</h2>';
            echo '<p>Looks like you have no items in your basket yet.</p>';
            echo '<a href="../index.php" class="continue-shopping">Continue Shopping</a>';
            echo '</div>';
        } else {
        ?>
        
        <table class="basket-table">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['basket'] as $book_id => $item): ?>
                <tr>
                    <td>
                        <div class="book-info">
                            <?php echo "<img src='../IMG/{$book_title}.png' alt='{$book_title}' class='book-image' \">"; ?>
                            <div>
                                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            </div>
                        </div>
                    </td>
                    <!-- https://www.w3schools.com/php/func_string_number_format.asp -->
                    <td>€<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="basket.php" method="POST">
                            <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                            <button type="submit" name="update_qty" class="update-btn">Update</button>
                        </form>
                    </td>
                    <!-- https://www.w3schools.com/php/func_string_number_format.asp -->
                    <td>€<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <form action="basket.php" method="POST">
                            <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                            <button type="submit" name="remove_item" class="remove-btn">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="basket-summary">
            <div class="summary-row total-row">
                <span>Total:</span>
                <span>€<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="basket-actions">
                <a href="../index.php" class="continue-shopping">Continue Shopping</a>
                <form action="checkout.php" method="POST">
                    <button type="submit" name="checkout" class="checkout-btn">Buy Now</button>
                </form>
            </div>
        </div>
        
        <?php } ?>
    </main>
    
    <?php include '../HTML/footer.html'; ?>
</body>
</html>