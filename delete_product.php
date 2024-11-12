<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php'; 

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Delete the product with the given product_id
    $query = "DELETE FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: total_products.php?message=Product+deleted+successfully");
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No product ID provided!";
}

$conn->close();
?>
