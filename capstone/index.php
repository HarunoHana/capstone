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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Homepage</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="images/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
    <a href="" class="navbar-brand p-0">
    <img src="images/log2.png" alt="IU Cupboard System Logo" id="header-image" style="vertical-align: middle; margin-top: -5px;">
    <h1 class="text-primary m-0" style="display: inline; vertical-align: middle;">IU Crimson Cupboard</h1>
</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
            <a href="index.php" class="nav-item nav-link active">Home</a>
            <a href="inventory.php" class="nav-item nav-link">Inventory</a>
            <a href="donor.php" class="nav-item nav-link">Donation</a>
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


        <div class="container-fluid bg-primary py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row justify-content-center py-5">
                    <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                        <h1 class="display-3 text-white mb-3 animated slideInDown">Indiana University Crimson Cupboard </h1>
                        <p class="fs-4 text-white mb-4 animated slideInDown">Fighting food insecurity on campus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100" src="images/cr.jpg" alt="" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">About Us</h6>
                    <h1 class="mb-4">Welcome to the <span class="text-primary">IU Cupboard System </span></h1>
                    <p class="mb-4">Crimson Cupboard provides complimentary basic needs access to students at Indiana University Bloomington facing challenges in securing consistent access to food. The concept of food insecurity encompasses the lack of reliable access to sufficient quantities of affordable, nutritious food. Operated through the generosity of donors and the dedication of volunteers, Crimson Cupboard plays a vital role in mitigating the impact of poverty and food scarcity within our community</p>
                    <div class="row gy-2 gx-4 mb-4">
                        <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Help our Community</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Provide an efficient online tool</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Fight food insecurity</p>
                        </div>
                    </div>
                    <a class="btn btn-primary py-3 px-5 mt-2" href="https://studentlife.indiana.edu/student-support/care-and-resource-center/food-pantry.html">Read More</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Services</h6>
                <h1 class="mb-5">Functions of Website</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-calendar text-primary mb-4"></i>
                            <h5>Scheduler</h5>
                            <p>Schedule donation drop off or item pickup</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-shopping-basket text-primary mb-4"></i>
                            <h5>Current Inventory</h5>
                            <p>Check current inventory of our system</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-edit text-primary mb-4"></i>
                            <h5>Recipe Generator</h5>
                            <p>Creates recipes based on cart contents with ChatGPT 3.5</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-cart-plus text-primary mb-4"></i>
                            <h5>Cart System</h5>
                            <p>Reserve items for pickup and use with Recipe Generator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="text-white mb-3">Contact</h4>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>800 N Union St, Bloomington, IN 47408</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>(812) 855-1924</p>
                <p class="mb-2"><i class="fa fa-building me-3"></i>Located in the basement of the Campus View Apartments</p>
            </div>
            <div class="col-lg-9 col-md-6">
                <h3 class="text-white mb-3">Location Map</h3>
                <iframe
                    width="100%"
                    height="450"
                    style="border:0"
                    loading="lazy"
                    allowfullscreen
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDjSkYPxWvKiNW5lL9DCereJ_X5usnP8J8&q=800+N+Union+St,+Bloomington,+IN+47408">
                </iframe>
            </div>
        </div>
    </div>
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="js/main.js"></script>

</body>

</html>

