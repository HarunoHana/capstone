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
    header('Location: login.php');
    exit();
}


// Check if the item_id is set in the query string
if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];
    $userId = $_SESSION['user_id'];

    // Prepare the SQL statement to remove the item from the cart
    $stmt = $pdo->prepare("DELETE FROM cart_item WHERE item_id = :item_id AND cart_id = (SELECT cart_id FROM cart WHERE user_id = :user_id LIMIT 1)");
    $stmt->execute([
        ':item_id' => $itemId,
        ':user_id' => $userId
    ]);

    // Check if the delete was successful
    if ($stmt->rowCount() > 0) {
        // Redirect back to the cart view with a success message
        header('Location: viewCart.php?status=removed');
    } else {
        // Redirect back to the cart view with an error message
        header('Location: viewCart.php?status=remove_failed');
    }
} else {
    // Redirect back to the cart view if the item_id is not set
    header('Location: viewCart.php?status=error');
}
?>