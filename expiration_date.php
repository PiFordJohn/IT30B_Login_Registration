<?php
include 'db_connection.php'; 

// Define default date range filter for expiring soon (next 30 days)
$today = date("Y-m-d");
$expiringSoonDate = date("Y-m-d", strtotime("+30 days"));

// Check if the user provided a specific filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build the SQL query based on the selected filter
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

if ($filter === 'expired') {
    // Show only expired products
    $query .= " WHERE product.expiration_date < ?";
    $dateParam = $today;
} elseif ($filter === 'expiring_soon') {
    // Show products expiring in the next 30 days
    $query .= " WHERE product.expiration_date BETWEEN ? AND ?";
    $dateParam1 = $today;
    $dateParam2 = $expiringSoonDate;
} 

// Prepare and execute the query
$stmt = $conn->prepare($query);

// Bind parameters if there are any filters applied
if ($filter === 'expired') {
    $stmt->bind_param('s', $dateParam);
} elseif ($filter === 'expiring_soon') {
    $stmt->bind_param('ss', $dateParam1, $dateParam2);
}

$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Expiration Dates</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1>Product Expiration Dates</h1>

    <!-- Filter options -->
    <div class="filter-bar">
        <form action="expiration_date.php" method="GET">
            <label for="filter">Filter:</label>
            <select name="filter" id="filter" onchange="this.form.submit()">
                <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Products</option>
                <option value="expired" <?php echo $filter === 'expired' ? 'selected' : ''; ?>>Expired Products</option>
                <option value="expiring_soon" <?php echo $filter === 'expiring_soon' ? 'selected' : ''; ?>>Expiring Soon</option>
            </select>
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
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="link-container">
        <a href="dashboard.php" class="back-link">Dashboard</a>
    </div>
</body>
</html>
