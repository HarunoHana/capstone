<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$host = 'db.luddy.indiana.edu';
$db = 'i494f23_team04';
$user = 'i494f23_team04';
$pass = 'my+sql=i494f23_team04';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function isAdmin() {
    return isset($_SESSION['name']) && $_SESSION['name'] === 'Admin';
}

$sortMethod = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
$orderBy = "ORDER BY item_name ASC"; 
if ($sortMethod === 'name_desc') $orderBy = "ORDER BY item_name DESC";
elseif ($sortMethod === 'category_asc') $orderBy = "ORDER BY item_category ASC";
elseif ($sortMethod === 'category_desc') $orderBy = "ORDER BY item_category DESC";

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
        .add-item-btn {
            background-color: crimson; 
            border-radius: 50%; 
            color: white; 
            font-size: 24px; 
            width: 50px; 
            height: 50px; 
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        }

        .add-item-btn:hover {
            background-color: darkred; 
            cursor: pointer;
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
    <img src="<?php echo $row['item_image'] ?: 'images/cr.jpg'; ?>" class="card-img-top" alt="Item Image">
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
        <p class="card-text">Category: <?php echo htmlspecialchars($row['item_category']); ?></p>
        <p class="card-text">Quantity: <?php echo htmlspecialchars($row['item_quantity']); ?></p>
        <?php if (isAdmin()): ?>
            <button type="button" class="btn btn-primary edit-button" data-bs-toggle="modal" data-bs-target="#editItemModal" data-item='<?php echo json_encode($row); ?>'>Edit</button>
            <button class="btn btn-danger delete-button" onclick="confirmDelete('<?php echo $row['item_id']; ?>')">Delete</button>
        <?php elseif(isset($_SESSION['user_id'])): ?> <!-- Check if user is logged in and not an admin -->
            <form action="addToCart.php" method="post" style="margin-top: 10px;">
        <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
        <input type="number" name="quantity" min="1" max="<?php echo $row['item_quantity']; ?>" value="1" required class="form-control mb-2">
        <button type="submit" class="btn btn-primary" name="add_to_cart">Add to Cart</button>
        </form>

        <?php endif; ?>
    </div>
</div>
<?php endwhile; ?>

</div>
</div>
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="addItem.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="addItemName" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemCategory" class="form-label">Category</label>
                        <input type="text" class="form-control" id="addItemCategory" name="item_category" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="addItemQuantity" name="item_quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="addItemImage" name="item_image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isAdmin()): ?>
    <button class="add-item-btn" data-bs-toggle="modal" data-bs-target="#addItemModal">+</button>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="editItem.php" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="item_id" id="modalItemId">
          <div class="mb-3">
            <label for="itemName" class="form-label">Name</label>
            <input type="text" class="form-control" id="modalItemName" name="item_name">
          </div>
          <div class="mb-3">
            <label for="itemCategory" class="form-label">Category</label>
            <input type="text" class="form-control" id="modalItemCategory" name="item_category">
          </div>
          <div class="mb-3">
            <label for="itemQuantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="modalItemQuantity" name="item_quantity">
          </div>
          <div class="mb-3">
  <label for="itemImage" class="form-label">Image</label>
  <input type="file" class="form-control" id="modalItemImage" name="item_image">
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="loginReminderModal" tabindex="-1" aria-labelledby="loginReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginReminderModalLabel">Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Please log in to use our system.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="login.php" class="btn btn-primary">Log In</a>
            </div>
        </div>
    </div>
</div>

<footer>
    &copy; FA23 Capstone Team 04
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.edit-button').on('click', function() {
        var item = $(this).data('item');
        $('#modalItemId').val(item.item_id);
        $('#modalItemName').val(item.item_name);
        $('#modalItemCategory').val(item.item_category);
        $('#modalItemQuantity').val(item.item_quantity);
    });
});

function confirmDelete(itemId) {
    if (confirm("Are you sure you want to delete this item?")) {
        window.location.href = "deleteItem.php?item_id=" + itemId;
    }
}
$(document).ready(function() {
        <?php if(!isset($_SESSION['user_id'])): ?>
            // Show the login reminder modal if the user is not logged in
            $('#loginReminderModal').modal('show');
        <?php endif; ?>
    });
</script>
</body>
</html>
