<?php
session_start();
require_once 'db_connect.php';
require_once 'basket_functions.php';

if (isset($_POST['checkout'])) {
    $total = calculateBasketSubtotal();
    
    if (!isset($_SESSION['basket']) || empty($_SESSION['basket'])) {
        $_SESSION['message'] = "Your basket is empty. Cannot proceed with checkout.";
        header('Location: basket.php');
        exit;
    }
    
    $pdo = db_connect();
    
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO orders (order_date, total_amount, status) VALUES (NOW(), :total, 'completed')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);//https://www.php.net/manual/en/pdo.constants.php
        $stmt->execute();
        
        $order_id = $pdo->lastInsertId();
        
        foreach ($_SESSION['basket'] as $book_id => $item) {
            $sql = "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (:order_id, :book_id, :quantity, :price)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
            $stmt->execute();
        }
        
        $pdo->commit();
        
        clearBasket();
        $_SESSION['message'] = "Thank you for your purchase!";
        header('Location: ../index.php');
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['message'] = "Error processing your order. Please try again.";
        header('Location: basket.php');
        exit;
    }
}

header('Location: basket.php');
exit;