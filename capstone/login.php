<?php

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="images/iu.png">
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

        /* Form styles */
        form {
            margin-top: 180px;
            text-align: center;
        }

        form div {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="password"] {
            padding: 10px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form button[type="submit"] {
            padding: 10px 20px;
            background-color: #990000;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button[type="submit"]:hover {
            background-color: #7a0000;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <img src="images/log.jpg" alt="IU Cupboard System Logo" id="header-image">
            <h1 style="display:inline;">IU Crimson Cupboard</h1>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Return to Homepage</a></li>
        </ul>
    </nav>
    <section>
        <h2>Login</h2>
        <form action="login_submit.php" method="POST">
            <div>
                <label for="IU_id">Username:</label>
                <input type="text" id="IU_id" name="IU_id" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </section>
</body>
</html>
