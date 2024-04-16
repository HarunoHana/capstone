<?php
session_start();

if (!isset($_SESSION['name']) || $_SESSION['name'] !== 'Admin') {
    echo "You are not authorized to view this page.";
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

    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    $item_name = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);
    $item_category = filter_input(INPUT_POST, 'item_category', FILTER_SANITIZE_STRING);
    $item_quantity = filter_input(INPUT_POST, 'item_quantity', FILTER_SANITIZE_NUMBER_INT);    

    $item_image = ''; 
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $target_dir = "images/";
        $originalName = basename($_FILES["item_image"]["name"]);
        $imageFileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $newFileName = pathinfo($originalName, PATHINFO_FILENAME) . "_" . time() . "." . $imageFileType;
        $target_file = $target_dir . $newFileName;

        $check = getimagesize($_FILES["item_image"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["item_image"]["size"] <= 5000000) {
                if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                    if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
                        $item_image = $target_file; 
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                        exit;
                    }
                } else {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    exit;
                }
            } else {
                echo "Sorry, your file is too large.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    $sql = "UPDATE item SET item_name = :item_name, item_category = :item_category, item_quantity = :item_quantity" .
           ($item_image ? ", item_image = :item_image" : "") . 
           " WHERE item_id = :item_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    $stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);
    $stmt->bindParam(':item_category', $item_category, PDO::PARAM_STR);
    $stmt->bindParam(':item_quantity', $item_quantity, PDO::PARAM_INT);

    if ($item_image) {
        $stmt->bindParam(':item_image', $item_image, PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Item updated successfully.'); window.location.href='inventory.php';</script>";
    } else {
        echo "Something went wrong. Please try again later.";
    }

} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

