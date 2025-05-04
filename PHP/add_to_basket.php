<?php
session_start();
require_once 'db_connect.php';
require_once 'basket_functions.php';

if (isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    
    $pdo = db_connect();
    $sql = "SELECT book_title, price FROM books WHERE book_id = :book_id AND book_status = 'A'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT); //https://www.php.net/manual/en/pdo.constants.php
    $stmt->execute();
    
    if ($book = $stmt->fetch()) {
        addToBasket($book_id, $book['book_title'], $book['price']);
    }
}

header('Location: ../index.php');
exit;
?>