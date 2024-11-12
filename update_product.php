<?php
include 'db_connection.php'; 

// Check if the product_id is passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Query to fetch the product details from the database
    $query = "SELECT * FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc(); 
    } else {
        echo "Product not found!";
        exit();
    }
    $stmt->close();
} else {
    echo "No product ID provided!";
    exit();
}

// Form submission for updating product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $total_stocks = $_POST['total_stocks'];
    $batch_date = $_POST['batch_date'];
    $expiration_date = $_POST['expiration_date'];
    
    // Query to update the product details in the database
    $updateQuery = "UPDATE product SET product_name = ?, total_stocks = ?, batch_date = ?, expiration_date = ? WHERE product_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sisss", $product_name, $total_stocks, $batch_date, $expiration_date, $product_id);
    
    if ($updateStmt->execute()) {
        // Redirect to the total_products.php page after successful update
        header("Location: total_products.php");
        exit();
    } else {
        echo "<p>Error updating product: " . $updateStmt->error . "</p>";
    }

    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <style>
        body {
            background-image: url('image/Tourist.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            padding-top: 50px;
            background-color: #f4f4f4;
            text-align: center;
        }
        h1 {
            color: orangered;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: orangered;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: darkorange;
        }
        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: orangered;
            text-decoration: none;
            font-size: 1.2em;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Update Product</h1>

    <form action="update_product.php?product_id=<?php echo $product_id; ?>" method="POST">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

        <label for="total_stocks">Total Stocks:</label>
        <input type="number" id="total_stocks" name="total_stocks" value="<?php echo htmlspecialchars($product['total_stocks']); ?>" required>

        <label for="batch_date">Batch Date:</label>
        <input type="date" id="batch_date" name="batch_date" value="<?php echo htmlspecialchars($product['batch_date']); ?>" required>

        <label for="expiration_date">Expiration Date:</label>
        <input type="date" id="expiration_date" name="expiration_date" value="<?php echo htmlspecialchars($product['expiration_date']); ?>" required>

        <input type="submit" value="Update Product">
    </form>
    <p><a href="total_products.php" class="back-link">Back to Products</a></p>

</body>
</html>
