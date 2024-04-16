<?php
session_start();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Dropoff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .navbar {
            background-image: url('images/back1.png'); 
            color: #ffffff; 
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important; 
        }
        .container.mt-5 {
            margin-top: 7rem !important; 
        }
        body {
            padding-top: 60px; 
            margin-top: 10px;
            padding-top: 1px; 
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
        .first {
            margin-top: 95px; 
            padding: 20px 0;
            text-align: center;
            background-color: transparent;
            font-family: 'Rubik One';
            font-size: 22px;;
        }
        .second{
            background-color:#dbdbdb;
            border-top: 2px solid #ccc;
        }
        form {
            width: 80%;
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            text-align: center;
            margin-top:1px;
            margin-bottom:1px;
        }
        .input-field {
            text-align: center; 
            background-color: white; 
            border: 1px solid #ddd;
            border-radius: 4px;
            height: 30px;
        }
        label, input, button {
            font-family: 'Rubik One', sans-serif; 
            display: block;
            width: 100%;
            margin-bottom: 10px;
            font-size: 19px;
        }
        .submit-button {
            width: 300px;
            padding: 10px;
            background-color: #9e9e9e; 
            color: white;
            border: none;
            border-radius: 17px;
            cursor: pointer;
            margin: auto; 
            display: block; 
        }
        .submit-button:hover {
            background-color: darkred; 
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0 fixed-top">
    <a href="" class="navbar-brand p-0">
        <img src="images/log2.png" alt="IU Cupboard System Logo" style="vertical-align: middle; margin-top: -5px;">
        <h1 class="text-primary m-0" style="display: inline; vertical-align: middle;">IU Crimson Cupboard</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
            <a href="index.php" class="nav-item nav-link">Home</a>
            <a href="inventory.php" class="nav-item nav-link">Inventory</a>
            <a href="donor.php" class="nav-item nav-link active">Donation</a>
            <a href="planner.php" class="nav-item nav-link">Meal Planner</a>
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
<section class="first">
    <h1 style="color: #990000;">Thank you for considering a donation!</h1> 
</section>
<section class="second">
    <form action="process_donation.php" method="post">
        <label for="date"><strong>Dropoff Schedule:</strong></label>
        <input type="date" id="date" name="date" required class="input-field">
        <br>
        <input type="time" id="time" name="time" required class="input-field">
        <br><br>
        <label for="item"><strong>Item Description:</strong></label>
        <input type="text" id="item" name="item" required class="input-field">
        <label for="quantity"><strong>Quantity:</strong></label>
        <input type="number" id="quantity" name="quantity" required class="input-field">
        <br><br><br>
        <button type="submit" class="submit-button">Submit Request</button>
    </form>
</section>
<footer>
    &copy; FA23 Capstone Team 04
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

