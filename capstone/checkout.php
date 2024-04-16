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

try {
    $userId = $_SESSION['user_id'];

    // Fetch the cart items for checkout summary
    $stmt = $pdo->prepare("SELECT ci.item_id, ci.item_quantity, i.item_name
                           FROM cart_item ci
                           JOIN item i ON ci.item_id = i.item_id
                           WHERE ci.cart_id = (SELECT cart_id FROM cart WHERE user_id = :user_id)");
    $stmt->execute([':user_id' => $userId]);
    $cartItems = $stmt->fetchAll();
} catch (\PDOException $e) {
    die("ERROR: Could not fetch cart items. " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .button, button {
            padding: 10px 20px;
            background-color: #990000; 
            color: #fff; 
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; 
            display: inline-block; 
        }

        .button:hover, button:hover {
            background-color: #7a0000; 
        }
    </style>
</head>
<body>
<section class="container">
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <h2>Order Summary</h2>
        <tbody>
    <?php foreach ($cartItems as $item): ?>
        <tr>
            <td>
                <strong>Name:</strong> <?= htmlspecialchars($item['item_name']); ?><br>
                Quantity: <?= htmlspecialchars($item['item_quantity']); ?><br><br>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>


        </table>
        <p>Thank you for your order. Press Complete Order and schedule a pickup time for your items in MyPage.</p>
    
        <form action="processCheckout.php" method="post">
            <input type="submit" value="Complete Order" class="button">
        </form>
        <?php endif; ?>


    <p><a href="viewCart.php" class="button">Return to Cart</a></p>
    <p><a href="planner.php" class="button">Go to Meal Planner</a></p>   
</section>
</body>
</html>
