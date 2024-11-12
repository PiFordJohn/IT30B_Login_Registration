<?php
include 'db_connection.php'; 

// search term (provided by the user)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$query = "
    SELECT 
        product.product_name, 
        product.product_id, 
        batch.batch_date, 
        batch.batch_number, 
        product.expiration_date, 
        product.total_stocks,
        supplier.supplier_name,
        category.category_name
    FROM 
        product
    LEFT JOIN batch ON product.batch_number = batch.batch_number
    LEFT JOIN supplier ON product.supplier_id = supplier.supplier_id
    LEFT JOIN category ON product.category_id = category.category_id
";

// Check if a search term is provided
if ($searchTerm) {
    if (strpos($searchTerm, 'supplier:') === 0) {
        // Search term starts with "supplier:", perform search on supplier name
        $query .= " WHERE supplier.supplier_name LIKE ?";
        $searchTerm = '%' . substr($searchTerm, 9) . '%';  
    } elseif (strpos($searchTerm, 'category:') === 0) {
        // Search term starts with "category:", perform search on category name
        $query .= " WHERE category.category_name LIKE ?";
        $searchTerm = '%' . substr($searchTerm, 9) . '%';  
    } elseif (strpos($searchTerm, 'batch_number:') === 0) {
        // Search term starts with "batch_number:", perform search on batch number
        $query .= " WHERE batch.batch_number LIKE ?";
        $searchTerm = '%' . substr($searchTerm, 13) . '%';  
    } else {
        // General search across multiple fields
        $query .= "
            WHERE 
                product.product_name LIKE ? OR 
                supplier.supplier_name LIKE ? OR 
                category.category_name LIKE ? OR 
                batch.batch_number LIKE ?";
        $searchTerm = '%' . $searchTerm . '%';  
    }
}

// Prepare and execute the query
$stmt = $conn->prepare($query);

// Bind the search term parameters if a WHERE clause is present
if (strpos($query, 'WHERE') !== false) {
    if (strpos($searchTerm, 'batch_number:') !== false) {
        $stmt->bind_param('s', $searchTerm);
    } else {
        $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }
}

$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Total Products</title>
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
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: orangered;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-bar input[type="submit"] {
            padding: 10px 20px;
            border-radius: 5px;
            background-color: orangered;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: darkorange;
        }
        .link-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .back-link, .back-to-original {
            display: inline-block;
            color: orangered;
            text-decoration: none;
            font-size: 1.2em;
        }
        .back-link:hover, .back-to-original:hover {
            text-decoration: underline;
        }
        .update-btn, .delete-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: orangered;
            color: white;
            flex-direction: column;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
        }
        .update-btn:hover, .delete-btn:hover {
            background-color: darkorange;
        }
    </style>
    <script>
        function confirmDelete(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                window.location.href = "delete_product.php?product_id=" + productId;
            }
        }
    </script>
</head>
<body>
    <h1>Total Products</h1>

    <div class="search-bar">
        <form action="total_products.php" method="GET">
            <input type="text" name="search" placeholder="Search for Supplier, Category, or Batch Number (e.g., 'supplier:Name')" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <input type="submit" value="Search">
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product ID</th>
                <th>Batch Date</th>
                <th>Batch Number</th>
                <th>Expiration Date</th>
                <th>Total Stocks</th>
                <th>Supplier Name</th>
                <th>Category Name</th>
                <th>Action</th> 
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['batch_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['batch_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_stocks']); ?></td>
                    <td><?php echo htmlspecialchars($row['supplier_name']) ? htmlspecialchars($row['supplier_name']) : 'No Supplier'; ?></td>
                    <td><?php echo htmlspecialchars($row['category_name']) ? htmlspecialchars($row['category_name']) : 'No Category'; ?></td>
                    <td>
                        <a href="update_product.php?product_id=<?php echo $row['product_id']; ?>" class="update-btn">Update</a>
                        <button onclick="confirmDelete(<?php echo $row['product_id']; ?>)" class="delete-btn">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="link-container">
        <a href="dashboard.php" class="back-link">Dashboard</a>
        <?php if ($searchTerm): ?>
            <a href="total_products.php" class="back-to-original">Back</a>
        <?php endif; ?>
    </div>
</body>
</html>
