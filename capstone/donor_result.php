<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Dropoff Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style2.css"> 
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            padding-top: 60px; 
        }
        .first {
            margin-top: 45px; 
            padding: 20px 0;
            text-align: center;
            background-color: transparent;
            font-family: 'Rubik One';
        }
        .second {
            background-color: #dbdbdb;
            border-top: 2px solid #ccc;
            text-align: center;
        }
        .second h2 {
            margin-top: 0; 
            padding-top: 10px; 
        }

        .last, .contact {
            width: 260px; 
            padding: 10px;
            background-color: #9e9e9e; 
            color: white;
            border: none;
            border-radius: 17px;
            cursor: pointer;
            margin: auto; 
            display: block; 
            font-size: 15px;
            text-align: center;
            text-decoration: none; 
            line-height: normal; 
            margin-bottom: 5px;
        }

        .last:hover, .contact:hover {
            background-color: darkred; 
        }
        .input-field {
            background-color: white; 
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 300px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .navbar {
            background-image: url('images/back1.png'); 
            color: #ffffff; 
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
    </div>
</nav>
    <section class="first">
    <h1 style='color: #990000; font-size: 30px;'>Your Request has been sent,<br> thank you for your<br> Donation!</h1>
    <?php
    if(isset($_SESSION['confirmation_num'])) {
        echo "<h2 style='color: black; font-size: 23px;'>Confirmation Number:<br>" . htmlspecialchars($_SESSION['confirmation_num']) . "</h2>";
        echo "<h2 style='color: black; font-size: 25px;'>*To ensure smooth and efficient processing, please keep your confirmation number handy*</h2>";
        unset($_SESSION['confirmation_num']);
    } else {
        echo "<h2 style='color: black; font-size: 25px;'>No confirmation number found.</h2>";
    }
    ?>
</section>
    <section class="second">
    <h2>Any Questions?</h2>
        <a href="#" class="contact" onclick="openModal()">Contact Us</a><br>
        <a href="index.php" class="last">Back to Main</a>
    </section>
    <div id="contactModal" class="modal">
    <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Email us at: cupboard@indiana.edu</p>
        </div>
    </div>
    <script>
        function openModal() {
            document.getElementById('contactModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('contactModal').style.display = 'none';
        }
        window.onclick = function(event) {
            var modal = document.getElementById('contactModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
