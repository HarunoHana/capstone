<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$sortMethod = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
switch ($sortMethod) {
    case 'name_asc':
        $orderBy = "ORDER BY item_name ASC";
        break;
    case 'name_desc':
        $orderBy = "ORDER BY item_name DESC";
        break;
    case 'category_asc':
        $orderBy = "ORDER BY item_category ASC";
        break;
    case 'category_desc':
        $orderBy = "ORDER BY item_category DESC";
        break;
    default:
        $orderBy = "ORDER BY item_name ASC";
        break;
}

$query = "SELECT item_id, item_name, item_category, item_quantity, item_image FROM item $orderBy";
$stmt = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
    <style>
    .navbar {
    background-image: url('images/back1.png'); 
    color: #ffffff; 
    }
    .navbar-brand, .nav-link {
        color: #ffffff !important; 
    }
    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }
    .card {
        width: 18rem;
        margin: 10px;
    }
    .card img {
        height: 180px;
        object-fit: cover;
    }
    .sort-method {
        margin: 20px 0;
    }
    .container.mt-5 {
        margin-top: 7rem !important; 
    }
</style>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0 fixed-top">
    <a href="" class="navbar-brand p-0">
        <img src="images/log2.png" alt="IU Cupboard System Logo" id="header-image" style="vertical-align: middle; margin-top: -5px;">
        <h1 class="text-primary m-0" style="display: inline; vertical-align: middle;">IU Crimson Cupboard</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
            <a href="index.php" class="nav-item nav-link">Home</a>
            <a href="inventory.php" class="nav-item nav-link active">Inventory</a>
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

    <div class="container mt-5">
        <div class="sort-method">
            <form action="" method="get">
                <select name="sort" onchange="this.form.submit()" class="form-select">
                    <option value="name_asc" <?php echo $sortMethod == 'name_asc' ? 'selected' : ''; ?>>Name Ascending</option>
                    <option value="name_desc" <?php echo $sortMethod == 'name_desc' ? 'selected' : ''; ?>>Name Descending</option>
                    <option value="category_asc" <?php echo $sortMethod == 'category_asc' ? 'selected' : ''; ?>>Category Ascending</option>
                    <option value="category_desc" <?php echo $sortMethod == 'category_desc' ? 'selected' : ''; ?>>Category Descending</option>
                </select>
            </form>
        </div>
        <div class="card-container">
        <?php while ($row = $stmt->fetch()): ?>
    <div class="card">
        <img src="<?php echo $row['item_image'] ?: 'path/to/default-image.jpg'; ?>" class="card-img-top" alt="Item Image">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
            <p class="card-text">Category: <?php echo htmlspecialchars($row['item_category']); ?></p>
            <p class="card-text">Quantity: <?php echo htmlspecialchars($row['item_quantity']); ?></p>
            <?php if (isAdmin()): ?>
                <button class="btn btn-primary edit-button" onclick="openEditForm('<?php echo $row['item_id']; ?>')">Edit</button>
                <button class="btn btn-danger delete-button" onclick="confirmDelete('<?php echo $row['item_id']; ?>')">Delete</button>
            <?php elseif (isset($_SESSION['user_id']) && $_SESSION['name'] !== 'Admin'): ?>
                <form action="addToCart.php" method="post" class="row">
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>" class="col-12">
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['item_quantity']; ?>" class="form-control mb-2 col-auto">
                    <div class="col-auto">
                        <button type="submit" name="add_to_cart" class="btn btn-primary addcart-button">Add to Cart</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php endwhile; ?>

    <script>
function openEditForm(itemId) {
    var modal = document.getElementById('editForm');
    modal.querySelector('input[name="item_id"]').value = itemId;
    $(modal).modal('show');
}

function confirmDelete(itemId) {
    if (confirm("Are you sure you want to delete this item?")) {
        window.location.href = "deleteItem.php?item_id=" + itemId;
    }
}
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
