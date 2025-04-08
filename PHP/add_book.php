<!-- add_book.php -->
<?php
session_start();
include_once 'db_connect.php';
// include '../HTML/add_book_header.html';

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
            echo("You did not complete the insert form correctly <br>");
        } else {
            $pdo = db_connect();

            // SQL query for insertion
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

            echo "Added! Try doing another.";
        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}

?>