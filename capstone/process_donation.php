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
    die("Could not connect to the database $db :" . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
    $item = filter_input(INPUT_POST, 'item', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $confirmationNum = rand(100000, 999999);
    $sql = "INSERT INTO donation (date, time, item, quantity, confirmation_num) VALUES (?, ?, ?, ?, ?)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date, $time, $item, $quantity, $confirmationNum]);
        $_SESSION['confirmation_num'] = $confirmationNum;
        header('Location: donor_result.php');
        exit; 
    } catch (\PDOException $e) {
        die("Failed to submit a request: " . $e->getMessage());
    }
} else {
    header('Location: donor.php');
    exit;
}
?>

