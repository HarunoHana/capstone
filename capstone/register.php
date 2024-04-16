<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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
        section {
            text-align: center;
            margin-top: 180px;
        }
        h2 {
            padding-top: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #990000;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
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
        <h2>Register an Account</h2>
        <form action="register_submit.php" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="userid">Username:</label>
                <input type="text" id="userid" name="userid" required>
            </div>
            <div>
                <label for="email">IU.edu Email:</label>
                <input type="email" id="email" name="email" pattern=".+@iu.edu" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </section>
</body>
</html>
