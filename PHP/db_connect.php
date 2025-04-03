<!-- db_connect.php -->
<?php
function db_connect() {
    $dbhost = 'localhost';
    $dbname = 'book_sales';
    $dbuser = 'root';
    $dbpassword = '';

    try {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $err) {
        echo "Database connection problem: " . $err->getMessage();
        exit();
    }
}
?>