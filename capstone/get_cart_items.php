<?php
session_start();

$host = 'db.luddy.indiana.edu';
$db = 'i494f23_team04';
$user = 'i494f23_team04';
$pass = 'my+sql=i494f23_team04';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$user_id = $_SESSION['user_id'];

// Your SQL query
$sql = "SELECT ci.cart_id, ci.item_id, ci.item_quantity, i.item_name, i.item_quantity AS stock_quantity
        FROM cart_item ci
        JOIN item i ON ci.item_id = i.item_id
        JOIN cart c ON ci.cart_id = c.cart_id
        WHERE c.user_id = :user_id";

// Prepare and execute the SQL query
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch the results
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($result);
?>
