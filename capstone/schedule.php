<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$host = 'db.luddy.indiana.edu';
$db   = 'i494f23_team04';
$user = 'i494f23_team04';
$pass = 'my+sql=i494f23_team04';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function isAdmin() {
    return isset($_SESSION['name']) && $_SESSION['name'] === 'Admin';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addAppointment'])) {
    $newDate = $_POST['date'];
    $newTime = $_POST['time'];
    $status = 'Scheduled';
    $userId = $_SESSION['user_id']; 

    $stmt = $pdo->prepare("INSERT INTO appointments (date, time, status, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$newDate, $newTime, $status, $userId]);

    header('Location: schedule.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Calendar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style2.css">
    <style>
        table {
            background-color: tan; 
            border-collapse: collapse; 
            width: 100%; 
            margin-top: 20px;
            border-spacing: 0; 
        }
        th, td {
            border: 1px solid red;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            background-color: #dbdbdb;
            height: 230px; 
            padding: 25px;
            margin: 0 auto;
            text-align: center;
            width: 100%;
            border-radius: 15px;
        }


body {
    margin-top: 80px;
    padding-top: 50px; 
    padding-bottom: 50px; 
}

header {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 0 10px; 
}

.form-container {
    max-width: 400px; 
    margin: 0 auto; 
}
.login-register-buttons, .logout-button {
    display: flex; 
    justify-content: flex-end; 
    gap: 10px; 
}

.login-register a, .mypage-logout-button a {
    display: inline-block; 
    text-decoration: none;
    color: black; 
    background-color: #dbdbdb; 
    padding: 8px 16px; 
    border-radius: 8px; 
    font-size: 16px; 
    border: 1px solid #c0c0c0; 
    text-align: center; 
}

.login-register a:hover, .mypage-logout-button a:hover {
            background-color: #c0c0c0; 
}
.login-register {
    padding: 10px;
    text-align: right; 
    display: flex; 
    flex-direction: column; 
    align-items: flex-end; 
}
.navbar {
    background-image: url('images/back1.png'); 
    color: #ffffff; 
}
.section-between {
    height: 50px; 
    background-color: white; 
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 23px;
}
.section-between h2 {
    color: #990000; 
}
.container input[type="date"],
.container input[type="time"] {
    width: 200px; 
    padding: 10px; 
    font-size: 16px; 
}
.container .form-inline {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.container .form-group {
    margin-bottom: 10px; 
}

.container .form-group button {
    margin-top: 10px; 
}



    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0 fixed-top">
        <a href="index.php" class="navbar-brand p-0">
            <img src="images/log2.png" alt="IU Cupboard System Logo" style="vertical-align: middle; margin-top: -5px;">
            <h1 class="text-primary m-0" style="display: inline; vertical-align: middle;">IU Crimson Cupboard</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
            </div>
            <?php if(isAdmin()): ?>
                <a href="admin.php" class="btn btn-primary rounded-pill py-2 px-4">Admin Page</a>
                <a href="logout.php" class="btn btn-primary rounded-pill py-2 px-4">Logout</a>
            <?php elseif(isset($_SESSION['user_id'])): ?>
                <a href="mypage.php" class="btn btn-primary rounded-pill py-2 px-4">My Page</a>
                <a href="logout.php" class="btn btn-primary rounded-pill py-2 px-4">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4">Login</a>
                <a href="register.php" class="btn btn-primary rounded-pill py-2 px-4">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<section class="section-between">
    <h2>Appointment Scheduler</h2>
</section>

<section class="container">
    <h1>Thank you for your request, see you soon!</h1>    
    <form action="schedule.php" method="post" class="form-inline">
        <div class="form-group">
            <input type="date" name="date" required>
            <input type="time" name="time" required>
        </div>
        <div class="form-group">
            <button type="submit" name="addAppointment" class="btn btn-primary">Add Appointment</button>
        </div>
    </form>
</section>



<footer>
    &copy; FA23 Capstone Team 04
</footer>
</body>
</html>
