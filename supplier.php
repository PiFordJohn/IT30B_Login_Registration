<?php
include 'db_connection.php';  // Database connection

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_name = $_POST['supplier_name'];

    // Insert into supplier table
    $stmt = $conn->prepare("INSERT INTO supplier (supplier_name) VALUES (?)");
    $stmt->bind_param("s", $supplier_name);

    if ($stmt->execute()) {
        echo "<p>Supplier added successfully!</p>";
    } else {
        echo "<p>Error adding supplier: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier</title>
    <style>
        body {
            background-image: url('image/Tourist.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f2f2f2;
        }
        h1 {
            color: orange;
        }
        form {
            display: inline-block;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        label{
            color:orangered;
            font-family: 'Courier New', Courier, monospace;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: orangered;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color:orangered;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Add New Supplier</h1>

    <form action="supplier.php" method="POST">
        <label>Supplier Name:</label>
        <input type="text" name="supplier_name" required>

        <input type="submit" value="Add Supplier">
    </form>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
