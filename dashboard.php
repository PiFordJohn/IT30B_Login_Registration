<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "userdb";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch counts for header
$totalProductsQuery = "SELECT COUNT(*) AS total FROM product";
$lowStockQuery = "SELECT COUNT(*) AS low_stock FROM product WHERE total_stocks < 10";
$outOfStockQuery = "SELECT COUNT(*) AS out_of_stock FROM product WHERE total_stocks = 0";

$totalProducts = $conn->query($totalProductsQuery)->fetch_assoc()['total'];
$lowStockProducts = $conn->query($lowStockQuery)->fetch_assoc()['low_stock'];
$outOfStock = $conn->query($outOfStockQuery)->fetch_assoc()['out_of_stock'];

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            background-image: url('image/Tourist.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
            padding-top: 50px;
        }
        h1 {
            font-size: 2.5em;
            color: orangered;
        }
        .header-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .header-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            width: 150px;
            transition: background 0.3s;
            text-decoration: none;
        }
        .header-item:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .header-item h2 {
            color: orangered;
            margin: 0;
            font-size: 2em;
        }
        .header-item p {
            color: white;
            font-size: 1.2em;
        }
        a {
            color: orangered;
            text-decoration: none;
        }
        .add-options {
            margin-top: 30px;
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .add-option-link {
            background-color: orangered;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2em;
        }
        .add-option-link:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

    <div class="header-container">
        <a href="total_products.php" class="header-item">
            <h2><?php echo $totalProducts; ?></h2>
            <p>Total Products</p>
        </a>
        <a href="low_stock_products.php" class="header-item">
            <h2><?php echo $lowStockProducts; ?></h2>
            <p>Low Stock</p>
        </a>
        <a href="out_of_stocks.php" class="header-item">
            <h2><?php echo $outOfStock; ?></h2>
            <p>Out of Stock</p>
        </a>
    </div>

    <!-- Add links for Add Product, Add Category, and Add Supplier -->
    <div class="add-options">
        <a href="add_product.php" class="add-option-link">Add Product</a>
        <a href="category.php" class="add-option-link">Add Category</a>
        <a href="supplier.php" class="add-option-link">Add Supplier</a>
        <!-- Add Update Products link -->
        <a href="total_products.php" class="add-option-link">Update Products</a>
    </div>

    <p><a href="logout.php" class="back-link">Logout</a></p>
</body>
</html>
