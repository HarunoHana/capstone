<?php
session_start();

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

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
    die("ERROR: Could not connect. " . $e->getMessage());
}

function isAdmin() {
    return isset($_SESSION['name']) && $_SESSION['name'] === 'Admin';
}

$stmt = $pdo->prepare("SELECT i.item_id, i.item_name, i.item_category, i.item_quantity as stock_quantity, i.item_image, ci.item_quantity as cart_quantity
                       FROM item i
                       JOIN cart_item ci ON i.item_id = ci.item_id
                       JOIN cart c ON ci.cart_id = c.cart_id
                       WHERE c.user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal Planner</title>
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
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 1px;
        }
        .card {
            width: 18rem;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }
        .card img {
            height: 180px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
            text-align: center;
        }
        body {
            padding-top: 60px; 
            margin-top: 10px;
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
            margin-top: 35px; 
            padding: 20px 0;
            text-align: center;
            background-color: transparent;
            font-family: 'Rubik One';
            font-size: 22px;
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
            <a href="planner.php" class="nav-item nav-link active">Meal Planner</a>
        </div>
        <?php if (function_exists('isAdmin') && isAdmin()): ?>
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
    <h1 style="color: #990000;">Meal Planner/Current Cart Items</h1> 
</section>
<section class="card-container">
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <div class="card">
                <img src="<?php echo $item['item_image'] ?: 'images/cr.jpg'; ?>" class="card-img-top" alt="Item Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($item['item_name']); ?></h5>
                    <p class="card-text">Category: <?php echo htmlspecialchars($item['item_category']); ?></p>
                    <p class="card-text">Quantity in Cart: <?php echo htmlspecialchars($item['cart_quantity']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>


    <div class="meal-plan-container" id="mealPlanContainer">

    </div>
    <div style="text-align: center;">
    <button class="btn btn-primary rounded-pill py-2" style="margin-top: 35px; margin-bottom: 35px;" onclick="getCartItems()">Generate Recipes</button>
    <div class="ai-response-container" id="ai-response-container"></div>
</div>


 <script>

    async function getCartItems() {
            try {
                // Retrieve items from the user
                const cartItemsResponse = await fetch('get_cart_items.php');
                const cartItems = await cartItemsResponse.json();

                // Process the cart items as needed
                console.log('Cart Items:', cartItems);

                // Get AI response based on cart items
                const cartItemNames = cartItems.map(item => item.item_name).join(', ');
                const formattedData = cartItemNames;

                // Pass the formatted data as a prompt to getChatGPTResponse
                await getChatGPTResponse(`Generate recipes based on the items in my cart. ${formattedData}`);
            } catch (error) {
                console.error('Error getting cart items:', error);
            }
        }

    async function getChatGPTResponse(prompt) {
    const apiUrl = 'https://api.openai.com/v1/chat/completions';
    const apiKey = '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';
    const model = 'gpt-3.5-turbo';

    try {
        const requestBody = {
            model: model,
            messages: [
                { role: 'system', content: 'assistant.' },
                { role: 'user', content: prompt },
            ],
        };

        console.log('Request Body:', requestBody);

        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiKey}`,
            },
            body: JSON.stringify(requestBody),
        });

        console.log('API Response:', response);

        if (!response.ok) {
            const errorResponse = await response.json();
            console.error('Error Response Body:', errorResponse);
            throw new Error(`HTTP error! Status: ${response.status}, Message: ${errorResponse.error.message}`);
        }

        const responseBody = await response.json();

       
        if (responseBody.choices && responseBody.choices.length > 0 && responseBody.choices[0].message && responseBody.choices[0].message.content) {
            const aiResponse = responseBody.choices[0].message.content.trim();
            // Handle the AI response as needed
            console.log('AI Response:', aiResponse);
            const aiResponseContainer = document.getElementById('ai-response-container');
            aiResponseContainer.innerHTML = `<p>AI Response: ${aiResponse}</p>`;
        } else {
            console.error('Invalid AI response structure:', responseBody);
        }
    } catch (error) {
        console.error('Error getting data from ChatGPT:', error);
    }
}


        // Call this function when you want to retrieve cart items and get AI response
        getCartItems();
</script>

    <footer>
        &copy; FA23 Capstone Team 04
    </footer>
</body>
</html>
