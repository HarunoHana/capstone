<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

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
    $userId = $_SESSION['user_id'];

    // Start transaction
    $pdo->beginTransaction();

    // Fetch cart_id for the user
    $cartIdStmt = $pdo->prepare("SELECT cart_id FROM cart WHERE user_id = :user_id");
    $cartIdStmt->execute([':user_id' => $userId]);
    $cartIdResult = $cartIdStmt->fetch();

    if (!$cartIdResult) {
        throw new Exception("Cart not found for user ID: " . $userId);
    }
    $cartId = $cartIdResult['cart_id'];

    // Fetch the cart items for the logged-in user
    $cartItemsStmt = $pdo->prepare("SELECT ci.item_id, ci.item_quantity FROM cart_item ci WHERE ci.cart_id = :cart_id");
    $cartItemsStmt->execute([':cart_id' => $cartId]);
    $cartItems = $cartItemsStmt->fetchAll();

    // Insert a new order into the orders table
    $insertOrderStmt = $pdo->prepare("INSERT INTO orders (cart_id, user_id, is_completed) VALUES (:cart_id, :user_id, 1)");
    $insertOrderStmt->execute([':cart_id' => $cartId, ':user_id' => $userId]);
    $orderId = $pdo->lastInsertId(); // Get the last inserted order id

    foreach ($cartItems as $item) {
        $insertOrderDetailStmt = $pdo->prepare("INSERT INTO order_details (order_id, item_id, quantity) VALUES (:order_id, :item_id, :quantity)");
        $insertOrderDetailStmt->execute([':order_id' => $orderId, ':item_id' => $item['item_id'], ':quantity' => $item['item_quantity']]);

        $updateInventoryStmt = $pdo->prepare("UPDATE item SET item_quantity = item_quantity - :quantity WHERE item_id = :item_id");
        $updateInventoryStmt->execute([':quantity' => $item['item_quantity'], ':item_id' => $item['item_id']]);
    }

    // Clear the user's cart after updating inventory
    $clearCartStmt = $pdo->prepare("DELETE FROM cart_item WHERE cart_id = :cart_id");
    $clearCartStmt->execute([':cart_id' => $cartId]);

    // Commit transaction
    $pdo->commit();

    // Redirect to the main page with a success message
    header('Location: mypage.php?order=success');
} catch (Exception $e) {
    // Roll back the transaction on error
    $pdo->rollBack();
    die("ERROR: Could not process the checkout. " . $e->getMessage());
}
?>
