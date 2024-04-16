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

    header('Location: mypage.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the saved carts for the user
$savedCarts = getSavedCarts($pdo, $user_id);

$user_id = $_SESSION['user_id'];
$sql = "SELECT date, time FROM appointments WHERE user_id = :user_id ORDER BY date ASC, time ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$appointments = $stmt->fetchAll();

// Function to get saved carts - This is the new function
function getSavedCarts($pdo, $userId) {
    $sql = "
    SELECT o.order_id, o.order_date, od.item_id, od.quantity, i.item_name 
    FROM orders o
    INNER JOIN order_details od ON o.order_id = od.order_id
    INNER JOIN item i ON od.item_id = i.item_id
    WHERE o.user_id = :user_id AND o.is_completed = 1
    ORDER BY o.order_date DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    // Group by order_id to facilitate displaying items per order
    return $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style2.css">
    <style>
        body {
            margin-top: 10px;
            padding-top: 60px; 
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
        .full-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: start;
            flex-wrap: wrap;
        }
        .section-box {
            flex: 1;
            margin: 10px;
            min-width: 280px;
            background-color: #ffffff; 
            border-radius: 8px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            padding: 20px; 
        }
        @media (max-width: 768px) {
            .full-container {
                flex-direction: column;
            }
        }
        .intropa {
            background-color: #f2f2f2; 
        }
        .navbar {
            background-image: url('images/back1.png'); 
            color: #ffffff; 
        }
        .null {
            margin-top: 30px; 
            padding: 20px 0;
            text-align: center;
            background-color: transparent;
            font-family: 'Rubik One';
            font-size: 22px;;
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


    <section class="null">
        <h1 class="textpro1" style="color: #990000;"><?php echo htmlspecialchars($_SESSION['name']); ?>'s<br> Crimson Cupboard profile</h1>
    </section>
    </section>
    <section class="intropa">
    <div class="container">
        <div class="full-container">
            <div class="section-box">
                <h1>Saved Carts</h1>
                <?php foreach ($savedCarts as $orderId => $items): ?>
                    <div class="order-history mb-3">
                        <h3>Order ID: <?= htmlspecialchars($orderId) ?></h3>
                        <p>Order Date: <?= htmlspecialchars($items[0]['order_date']) ?></p>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="section-box">
                <h1>Booked Appointments</h1>
                <ul>
                    <?php foreach ($appointments as $appointment): ?>
                        <li>
                            <strong>Date:</strong> <?= htmlspecialchars($appointment['date']) ?><br>
                            <strong>Time:</strong> <?= htmlspecialchars($appointment['time']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="section-box">
                <h1>Appointment Scheduler</h1>
                <form action="mypage.php" method="post">
                    <div style="margin-bottom: 10px;"> 
                        <input type="date" name="date" required style="margin-right: 5px;"> 
                        <input type="time" name="time" required>
                    </div>
                    <button type="submit" name="addAppointment" class="btn btn-primary">Add Appointment</button>
                </form>
            </div>
        </div>
    </div>
</section>
<footer>
    &copy; FA23 Capstone Team 04
</footer>
<script src="https://apis.google.com/js/platform.js" async defer></script>
</body>
</html>




