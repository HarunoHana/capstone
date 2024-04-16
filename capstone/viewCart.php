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

// Redirect user to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch cart items for the logged-in user
try {
    $stmt = $pdo->prepare("SELECT ci.cart_id, ci.item_id, ci.item_quantity, i.item_name, i.item_quantity AS stock_quantity
                           FROM cart_item ci
                           JOIN item i ON ci.item_id = i.item_id
                           JOIN cart c ON ci.cart_id = c.cart_id
                           WHERE c.user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll();
} catch (\PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
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

        .cart-item {
            margin-bottom: 15px; 
        }
    </style>
</head>
<body>
    <section class="container">
        <h1>Your Shopping Cart</h1>

        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <p style="font-weight: bold;">Name: <?= htmlspecialchars($item['item_name']); ?></p>
                    <p>Quantity: <?= htmlspecialchars($item['item_quantity']); ?>
                    </p>
                    <button onclick="openModal(<?= $item['item_id']; ?>, <?= $item['item_quantity']; ?>)">Update</button>
                        <a href="removeFromCart.php?item_id=<?= $item['item_id']; ?>" class="button">Remove</a>
                </div>
            <?php endforeach; ?>
            <div class="actions">
                <a href="checkout.php" class="button">Proceed to Checkout</a>
                <a href="inventory.php" class="button">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- The Modal -->
    <div id="quantityModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Update Quantity</h2>
            <input type="range" id="quantityRange" min="1" max="10" value="1" oninput="updateQuantityValue(this.value)">
            <p>Quantity: <span id="quantityValue">1</span></p>
            <button onclick="updateCartItem()">Update</button>
        </div>
    </div>   
    
    <script>
        var currentUpdatingItemId; // Global variable to keep track of the item being updated

        function openModal(cartItemId, currentQuantity) {
            currentUpdatingItemId = cartItemId; // Set the current item ID
            document.getElementById('quantityModal').style.display = 'block';
            document.getElementById('quantityRange').value = currentQuantity;
            document.getElementById('quantityValue').innerText = currentQuantity;
        }

        function closeModal() {
            document.getElementById('quantityModal').style.display = 'none';
        }

        function updateQuantityValue(value) {
            document.getElementById('quantityValue').innerText = value;
        }

        function updateCartItem() {
            var quantity = document.getElementById('quantityRange').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateCart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    closeModal();
                    location.reload();
                }
            };
            xhr.send('cart_item_id=' + currentUpdatingItemId + '&new_quantity=' + quantity);
        }
    </script>
</body>
</html>

