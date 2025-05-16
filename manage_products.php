<?php
session_start();
require 'db.php';

// Ensure the user is logged in and is an admin or super admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Check if the barcode already exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE barcode = ?");
    $stmt->execute([$barcode]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // If the product already exists, update it
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE barcode = ?");
        $stmt->execute([$name, $description, $price, $stock, $barcode]);
        echo "Product updated successfully!";
    } else {
        // Insert new product into the database
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, barcode) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $barcode]);
        echo "Product added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E8EFCF;
            margin: 0;
            padding: 0;
        }

        form {
            width: 80%;
            margin: 30px auto;
            padding: 30px;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form input[type="text"],
        form input[type="number"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ECCA9C;
            background-color: #E8EFCF;
            color: #4B4B4B;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #AFD198;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #DBA979;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Add or Edit Product</h2>
        <input type="text" name="barcode" placeholder="Barcode" required>
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="price" placeholder="Price" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <button type="submit">Save Product</button>
    </form>
</body>
</html>
