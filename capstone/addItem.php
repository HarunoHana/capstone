<?php
session_start();


if (!isset($_SESSION['name']) || $_SESSION['name'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

$host = 'db.luddy.indiana.edu';
$db = 'i494f23_team04';
$user = 'i494f23_team04';
$pass = 'my+sql=i494f23_team04';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $itemImage = 'images/cr.jpg'; 

        $itemName = trim($_POST['item_name']);
        $itemCategory = trim($_POST['item_category']);
        $itemQuantityInput = trim($_POST['item_quantity']);

        $itemQuantity = filter_var($itemQuantityInput, FILTER_VALIDATE_INT);
        if ($itemQuantity === false) {
            echo "Item quantity needs to be an integer.";
            exit;
        }

        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
            $target_dir = "images/";
            $originalName = basename($_FILES["item_image"]["name"]);
            $imageFileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $target_file = $target_dir . pathinfo($originalName, PATHINFO_FILENAME) . "_" . time() . "." . $imageFileType;

            $check = getimagesize($_FILES["item_image"]["tmp_name"]);
            if ($check !== false && $_FILES["item_image"]["size"] <= 5000000 && in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
                    $itemImage = $target_file; 
                } else {
                    echo "Sorry, there was an error uploading your file. Using default image.";
                }
            } else {
                echo "Sorry, your file was not uploaded. Using default image.";
            }
        }

        $sql = "INSERT INTO item (item_name, item_category, item_quantity, item_image) VALUES (:name, :category, :quantity, :item_image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $itemName, PDO::PARAM_STR);
        $stmt->bindParam(':category', $itemCategory, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $itemQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':item_image', $itemImage, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: inventory.php?status=success");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
} catch (\PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
