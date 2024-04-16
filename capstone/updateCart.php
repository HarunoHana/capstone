<?php
session_start();

// Database connection setup
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

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in.";
    exit;
}



// Check if this is an AJAX request
if (!empty($_POST['cart_item_id']) && !empty($_POST['new_quantity'])) {
    $cartItemId = $_POST['cart_item_id'];
    $newQuantity = $_POST['new_quantity'];
    $userId = $_SESSION['user_id'];

    // Validate the new quantity
    if ($newQuantity > 0) {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Prepare the SQL statement to update the cart item quantity
            $stmt = $pdo->prepare("UPDATE cart_item SET item_quantity = :new_quantity WHERE item_id = :cart_item_id AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = :user_id)");
            $stmt->execute([
                ':new_quantity' => $newQuantity,
                ':cart_item_id' => $cartItemId,
                ':user_id' => $userId
            ]);

            // Commit the transaction
            $pdo->commit();

            if ($stmt->rowCount() > 0) {
                echo "Quantity updated successfully.";
            } else {
                echo "No changes made to the quantity.";
            }
        } catch (Exception $e) {
            // Rollback the transaction if anything goes wrong
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid quantity.";
    }
} else {
    echo "Invalid request.";
}
?>
