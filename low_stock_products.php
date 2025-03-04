<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php'; 

// Query to get products with low stock (total_stocks < 10)
$query = "SELECT product_id, product_name, total_stocks FROM product WHERE total_stocks < 10";
$result = $conn->query($query);

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #ff4500;
        }
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #dddddd;
        }
        th {
            background-color: #ff4500;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Low Stock Products</h1>
    
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Total Stocks</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_stocks']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No low stock products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>

