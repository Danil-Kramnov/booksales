<?php

function getBasketItemCount() {
    $itemCount = 0;
    if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
        foreach ($_SESSION['basket'] as $item) {
            $itemCount += $item['quantity'];
        }
    }
    return $itemCount;
}

function calculateBasketSubtotal() {
    $subtotal = 0;
    if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
        foreach ($_SESSION['basket'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
    return $subtotal;
}

function addToBasket($book_id, $title, $price) {
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = array();
    }
    
    if (isset($_SESSION['basket'][$book_id])) {
        $_SESSION['basket'][$book_id]['quantity']++;
    } else {
        $_SESSION['basket'][$book_id] = array(
            'title' => $title,
            'price' => $price,
            'quantity' => 1
        );
    }
    
    $_SESSION['message'] = "Item added to basket.";
}

function removeFromBasket($book_id) {
    $book_id = (int)$book_id;
    if (isset($_SESSION['basket'][$book_id])) {
        unset($_SESSION['basket'][$book_id]);
        $_SESSION['message'] = "Item removed from basket.";
    }
}

function updateBasketQuantity($book_id, $quantity) {
    $book_id = (int)$book_id;
    $quantity = (int)$quantity;
    
    if ($quantity <= 0) {
        removeFromBasket($book_id);
    } else {
        if (isset($_SESSION['basket'][$book_id])) {
            $_SESSION['basket'][$book_id]['quantity'] = $quantity;
            $_SESSION['message'] = "Basket updated.";
        }
    }
}

function clearBasket() {
    $_SESSION['basket'] = array();
}
?>