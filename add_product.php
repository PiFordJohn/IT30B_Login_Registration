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

// Fetch categories and suppliers for dropdowns
$categoryQuery = "SELECT category_id, category_name FROM category";
$categoryResult = $conn->query($categoryQuery);

$supplierQuery = "SELECT supplier_id, supplier_name FROM supplier";
$supplierResult = $conn->query($supplierQuery);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_id = $_POST['product_id'];
    $batch_date = $_POST['batch_date'];
    $batch_number = $_POST['batch_number'];
    $expiration_date = $_POST['expiration_date'];
    $total_stocks = $_POST['total_stocks'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];

    // Insert product into the product table
    $stmt = $conn->prepare("INSERT INTO product (product_name, product_id, batch_date, batch_number, expiration_date, total_stocks, category_id, supplier_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiii", $product_name, $product_id, $batch_date, $batch_number, $expiration_date, $total_stocks, $category_id, $supplier_id);

    if ($stmt->execute()) {
        echo "<p>Product added successfully!</p>";
    } else {
        echo "<p>Error adding product: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        /* Styles */
        body {
            background-image: url('image/Tourist.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f2f2f2;
        }
        h1 {
            color: #333;
        }
        form {
            display: inline-block;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="date"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            font-weight: 400;
            font-size: x-large;
            color: orangered;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Add New Product</h1>

    <form action="add_product.php" method="POST">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>

        <label>Product ID:</label>
        <input type="text" name="product_id" required>

        <label>Batch Date:</label>
        <input type="date" name="batch_date" required>

        <label>Batch Number:</label>
        <input type="text" name="batch_number" required>

        <label>Expiration Date:</label>
        <input type="date" name="expiration_date" required>

        <label>Total Stocks:</label>
        <input type="number" name="total_stocks" required>

        <label>Category:</label>
        <select name="category_id" required>
            <?php while ($row = $categoryResult->fetch_assoc()): ?>
                <option value="<?php echo $row['category_id']; ?>"><?php echo htmlspecialchars($row['category_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Supplier:</label>
        <select name="supplier_id" required>
            <?php while ($row = $supplierResult->fetch_assoc()): ?>
                <option value="<?php echo $row['supplier_id']; ?>"><?php echo htmlspecialchars($row['supplier_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <input type="submit" value="Add Product">
    </form>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
