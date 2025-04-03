<!-- delete_book.php -->
<?php
include_once 'db_connect.php';

if (isset($_POST['cid']) && isset($_POST['delete_book'])) {
    try {
        $pdo = db_connect();

        $book_id = $_POST['cid'];

        // If the user confirmed, proceed with deletion
        if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
            // Delete the book
            $sql = 'DELETE FROM books WHERE book_id = :cid';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cid', $book_id);
            $stmt->execute();

            $sql = 'SELECT * FROM books WHERE book_id = :cid';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cid', $book_id);
            $stmt->execute();
            $book = $stmt->fetch();

            echo "You deleted the book: <strong>" . htmlspecialchars($book['book_title']) . "</strong> by " . htmlspecialchars($book['author']) . ".<br>";
            echo 'Click <a href="../index.php">here</a> to go back.';
        } else {
            $sql = 'SELECT * FROM books WHERE book_id = :cid';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cid', $book_id);
            $stmt->execute();

            if ($row = $stmt->fetch()) {
                echo "Are you sure you want to delete the book: <strong>" . htmlspecialchars($row['book_title']) . "</strong> by " . htmlspecialchars($row['author']) . "?<br>";

                echo '<form action="" method="post">
                        <input type="hidden" name="cid" value="' . htmlspecialchars($book_id) . '">
                        <input type="hidden" name="confirm_delete" value="yes">
                        <input type="submit" name="delete_book" value="Yes, Delete">
                      </form>';
                
                echo '<form action="../index.php" method="post">
                        <input type="submit" value="No, go back">
                      </form>';
            } else {
                echo "No book found with that ID.";
            }
        }

    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
} else {
    echo "No book ID was provided. Please go back and try again.";
}
?>
