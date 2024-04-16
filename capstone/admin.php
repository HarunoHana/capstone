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
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function isAdmin() {
    return isset($_SESSION['name']) && $_SESSION['name'] === 'Admin';
}

$appointmentsStmt = $pdo->query('SELECT appointments.app_id, appointments.date, appointments.time, appointments.status, user.name, user.email 
                                  FROM appointments 
                                  JOIN user ON appointments.user_id = user.user_id');
$appointments = $appointmentsStmt->fetchAll();

$donationsStmt = $pdo->query('SELECT date, time, item, quantity, confirmation_num FROM donation');
$donations = $donationsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
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
        }
        .appdash {
            text-align: center;
            background-color: transparent;
            font-family: 'Rubik One', sans-serif;
            font-size: 32px;
            color: #990000;
            font-weight: bold; 
            margin-top: 45px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
            <a href="donor.php" class="nav-item nav-link">Donation</a>
            <a href="planner.php" class="nav-item nav-link">Meal Planner</a>
        </div>
        <?php if(isAdmin()): ?>
            <a href="admin.php" class="btn btn-primary rounded-pill py-2 px-4">Admin Page</a>
            <a href="logout.php" class="btn btn-primary rounded-pill py-2 px-4">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4">Login</a>
            <a href="register.php" class="btn btn-primary rounded-pill py-2 px-4">Register</a>
        <?php endif; ?>
    </div>
</nav>
<section>
    <div class="appdash">Appointment Dashboard</div>
    <?php
    if (count($appointments) > 0) {
        echo "<table>
                <tr>
                    <th>Appointment ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Name</th>
                    <th>User Email</th>
                    <th></th>
                </tr>";
        foreach ($appointments as $row) {
            echo "<tr>
                    <td>{$row['app_id']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['time']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td><a href='edit_admin.php?id=" . $row["app_id"] . "'>Edit</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No appointment data available.";
    }
    ?>

    <div class="appdash">Donation Dashboard</div>
    <?php
    if (count($donations) > 0) {
        echo "<table>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Confirmation Number</th>
                </tr>";
        foreach ($donations as $row) {
            echo "<tr>
                    <td>{$row['date']}</td>
                    <td>{$row['time']}</td>
                    <td>{$row['item']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['confirmation_num']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No donation data available.";
    }
    ?>
</section>
<footer>
    &copy; FA23 Capstone Team 04
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
