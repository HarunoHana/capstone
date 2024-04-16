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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $IU_id = trim($_POST['IU_id']);
        $password = trim($_POST['password']);
        
        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE IU_id = :IU_id");
        $stmt->bindParam(':IU_id', $IU_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $password == $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name']; 
            
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Invalid IU ID or Password.'); window.location.href='login.php';</script>";
        }
    }
} catch (\PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

