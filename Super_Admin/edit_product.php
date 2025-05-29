<?php
require __DIR__ . '/../db.php';

// Fetch product by ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p style='color:red;'>Product not found!</p>";
        exit();
    }
}

// Handle product update
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $barcode = $_POST['barcode'];

    $updateSql = "UPDATE products SET 
                    name = ?, description = ?, price = ?, 
                    stock = ?, barcode = ?
                  WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssdiii", $name, $description, $price, $stock, $barcode, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            /* Make sure the body takes the full height */
            background-image: url('../indexpic/preview.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #EC6D10;
            padding: 15px;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar h2 img {
            height: 40px;
            width: auto;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Container Styles */
        .container {

            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin-left: 625px;
            /* Adjust for sidebar width */
            position: relative;
            z-index: 1;
            /* Ensure it stays on top */
        }

        h2 {
            margin-bottom: 20px;
            color: white;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left;
            color: white;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: rgba(255, 149, 0, 0.86);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: rgba(255, 149, 2, 0.7);
            /* Lighter color on hover */
        }

        .cancel-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #a10303;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .cancel-button:hover {
            background-color: #6b0202;
            /* Darker shade of the original color */
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>
            <img src="../imgmain/logo1.png" alt="image">
        </h2>
        <ul>
            <li><a href="super_admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="categories.php"><i class="fas fa-list"></i> Categories</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="suppliers.php"><i class=" fas fa-address-card"></i>Suppliers</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>Edit Product</h2>

        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">

            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($row['name']); ?>" required>

            <label for="description">Product Description:</label>
            <textarea name="description" id="description" placeholder="Enter product description"><?= htmlspecialchars($row['description']); ?></textarea>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="<?= htmlspecialchars($row['price']); ?>" step="0.01" required>

            <label for="stock">Stock Quantity:</label>
            <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($row['stock']); ?>" required>

            <label for="barcode">Barcode:</label>
            <input type="text" name="barcode" id="barcode" value="<?= htmlspecialchars($row['barcode']); ?>" required>

            <button type="submit" name="update">Update Product</button>
        </form>

        <a href="products.php" class="cancel-button">Cancel</a>
    </div>

</body>

</html>