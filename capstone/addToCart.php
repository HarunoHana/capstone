<?php
session_start();

// Database connection details
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
    die("ERROR: Could not connect. " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$validateUserIdStmt = $pdo->prepare("SELECT 1 FROM user WHERE user_id = :user_id");
$validateUserIdStmt->execute([':user_id' => $_SESSION['user_id']]);
if ($validateUserIdStmt->fetchColumn() === false) {
    die("Invalid user session. Please log in again.");
}

function ensureCartExists($userId, $pdo) {
    $stmt = $pdo->prepare("SELECT cart_id FROM cart WHERE user_id = :user_id LIMIT 1");
    $stmt->execute([':user_id' => $userId]);
    $cart = $stmt->fetch();

    if (!$cart) {
        // If the user does not have a cart, create one
        $insertStmt = $pdo->prepare("INSERT INTO cart (user_id) VALUES (:user_id)");
        $insertStmt->execute([':user_id' => $userId]);
        return $pdo->lastInsertId(); 
    }

    return $cart['cart_id']; // Return the existing cart ID
}

function addToCart($userId, $itemId, $quantity, $pdo) {
    $pdo->beginTransaction();

    try {
        $cartId = ensureCartExists($userId, $pdo); 

        // Check if the item exists and has enough stock
        $itemCheckStmt = $pdo->prepare("SELECT item_quantity FROM item WHERE item_id = :item_id");
        $itemCheckStmt->execute([':item_id' => $itemId]);
        $item = $itemCheckStmt->fetch();

        if (!$item || $item['item_quantity'] < $quantity) {
            throw new Exception('Not enough stock or item does not exist.');
        }

        // Check if the item is already in the cart
        $cartItemCheckStmt = $pdo->prepare("SELECT item_quantity FROM cart_item WHERE cart_id = :cart_id AND item_id = :item_id");
        $cartItemCheckStmt->execute([':cart_id' => $cartId, ':item_id' => $itemId]);
        $cartItem = $cartItemCheckStmt->fetch();

        if ($cartItem) {
            // Item is already in the cart, update the quantity
            $newQuantity = $cartItem['item_quantity'] + $quantity;
            $updateStmt = $pdo->prepare("UPDATE cart_item SET item_quantity = :item_quantity WHERE cart_id = :cart_id AND item_id = :item_id");
            $updateStmt->execute([':item_quantity' => $newQuantity, ':cart_id' => $cartId, ':item_id' => $itemId]);
        } else {
            // Item is not in the cart, insert new
            $insertStmt = $pdo->prepare("INSERT INTO cart_item (cart_id, item_id, item_quantity) VALUES (:cart_id, :item_id, :item_quantity)");
            $insertStmt->execute([':cart_id' => $cartId, ':item_id' => $itemId, ':item_quantity' => $quantity]);
        }

        $pdo->commit();
        header('Location: viewCart.php'); // Redirect to cart view page
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_POST['add_to_cart'])) {
    $userId = $_SESSION['user_id'];
    $itemId = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    addToCart($userId, $itemId, $quantity, $pdo);
} else {
    header('Location: inventory.php');
    exit();
}
?>
