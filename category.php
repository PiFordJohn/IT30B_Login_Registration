<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include 'db_connection.php';  

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    // Insert into category table in userdb database
    $stmt = $conn->prepare("INSERT INTO category (category_name) VALUES (?)");
    $stmt->bind_param("s", $category_name);

    if ($stmt->execute()) {
        echo "<p>Category added successfully!</p>";
    } else {
        echo "<p>Error adding category: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
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
            color: orangered;
            text-decoration: none;
            font-family: Arial, sans-serif;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Add New Category</h1>

    <form action="category.php" method="POST">
        <label>Category Name:</label>
        <input type="text" name="category_name" required>

        <input type="submit" value="Add Category">
    </form>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
