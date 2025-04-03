<!-- update_book.php -->
<?php
include_once 'PHP/db_connect.php';

if (isset($_POST['update_book'])) {

    $cbook_title = $_POST['cbook_title'];
    $cauthor = $_POST['cauthor'];
    $cisbn = $_POST['cisbn'];
    $cgenre_code = $_POST['cgenre_code'];
    $cprice = $_POST['cprice'];
    $cstock_amount = $_POST['cstock_amount'];
    $cbook_status = $_POST['cbook_status'];

    try {
        $pdo = db_connect();

            $sql = "UPDATE books 
                    SET book_title = :cbook_title, 
                        author = :cauthor, 
                        ISBN = :cisbn, 
                        genre_code = :cgenre_code, 
                        price = :cprice, 
                        stock_amount = :cstock_amount, 
                        book_status = :cbook_status 
                    WHERE book_id = :cbook_id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cbook_title', $cbook_title);
            $stmt->bindValue(':cauthor', $cauthor);
            $stmt->bindValue(':cisbn', $cisbn);
            $stmt->bindValue(':cgenre_code', $cgenre_code);
            $stmt->bindValue(':cprice', $cprice);
            $stmt->bindValue(':cstock_amount', $cstock_amount);
            $stmt->bindValue(':cbook_status', $cbook_status);
            $stmt->execute();

            echo "Added! Try doing another.";

        echo "Book updated successfully!<br>";
        echo '<a href="index.php">Return to Home</a>';
    } catch (PDOException $e) {
        echo "Error updating book: " . $e->getMessage();
    }
    exit();
} elseif (isset($_POST['cid'])) {
    // retrieve the book details to populate form
    $book_id = $_POST['cid'];
    try {
        $pdo = db_connect();
        $sql = "SELECT * FROM books WHERE book_id = :cbook_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cbook_id', $cbook_id);
        $stmt->execute();
        $book = $stmt->fetch();

        if ($book) {
            // set variables for the update form
            $cbook_id = $book['book_id'];
            $cbook_title = $book['book_title'];
            $cauthor = $book['author'];
            $cisbn = $book['ISBN'];
            $cgenre_code = $book['genre_code'];
            $cprice = $book['price'];
            $cstock_amount = $book['stock_amount'];
            $cbook_status = $book['book_status'];
        } else {
            echo "No book found with that ID";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error fetching book: " . $e->getMessage();
        exit();
    }
    include 'update_book_form.html';
} else {
    echo "Invalid request";
}
?>
