<?php
session_start();

$host = 'db.luddy.indiana.edu';
$db = 'i494f23_team04';
$user = 'i494f23_team04';
$pass = 'my+sql=i494f23_team04';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

    // check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

function isAdmin() {
    return isset($_SESSION['name']) && $_SESSION['name'] === 'Admin';
}

print_r($_SESSION);
$IU_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile Info</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
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

        header > div:first-child {
            display: flex; 
            align-items: center; 
        }

        #header-image {
            vertical-align: middle;
            margin-right: 10px; 
        }

        .login-register {
            padding: 10px;
            text-align: right; 
            display: flex; 
            flex-direction: column; 
            align-items: flex-end; 
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
    </style>
</head>
<body>
    <header>
        <div>
            <img src="images/log.jpg" alt="IU Cupboard System Logo" id="header-image">
            <h1 style="display:inline;">IU Cupboard System</h1>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="mypage.php">Return to My Page</a></li>
        </ul>
    </nav>
    <section style='text-align: center; margin-top: 180px;'>
    <h2>Edit your Account</h2>
    <?php
    // if (isset($_SESSION["id"])) {
    //     $schedule_id = $_GET["id"];
    //     $select_sql = "SELECT * FROM links WHERE schedule_id=$schedule_id";
    //     $result = $conn->query($select_sql);

    //     if ($result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    ?>
    <form action="edit_submit.php" method="POST">
        <div>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($u_name); ?>" required/>
        </div> 
        <div>
            <label for="userid">Username:</label>
            <input type="text" id="userid" name="userid" value="<?php echo htmlspecialchars($u_IU_id); ?>" required/>
        </div>
        <!-- <div>
            <label for="email">IU.edu Email:</label>
            <input type="email" id="email" name="email" pattern=".+@iu.edu" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div> -->
        <button type="submit">Register</button>
    </form>
    <?php 
    $conn->close(); ?>
    </section>
    <footer>
        &copy; FA23 Capstone Team 04
    </footer>
</body>
</html>
