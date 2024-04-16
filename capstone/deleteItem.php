<?php
session_start();
if (!isset($_SESSION['name']) || $_SESSION['name'] !== 'Admin') {
    header('Location: login.php');
    exit;
}
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

$item_id = $_GET['item_id'] ?? null; 

if ($item_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM item WHERE item_id = :item_id");
        $stmt->execute([':item_id' => $item_id]);
        if ($stmt->rowCount()) {
            $_SESSION['message'] = "Item deleted successfully.";
        } else {
            $_SESSION['error'] = "Item deletion failed or item does not exist.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "No item ID provided.";
}

header('Location: inventory.php');
exit();
?>
