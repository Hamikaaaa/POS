<?php include '../db.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['item_name'];
    $category = $_POST['category'];
    $unit = $_POST['unit'];
    $stock = (int)$_POST['stock']; // Force whole number for stock
    $reorder = (int)$_POST['reorder_level']; // Force whole number for reorder level
    $cost = $_POST['cost_price'];
    $supplier = $_POST['supplier_id'] ? $_POST['supplier_id'] : NULL; // If supplier_id is not set, use NULL

    // Prepare and execute the SQL insert statement
    $stmt = $pdo->prepare("INSERT INTO inventory_items (item_name, category, unit, current_stock, reorder_level, cost_price, supplier_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category, $unit, $stock, $reorder, $cost, $supplier]);

    // Success message
    echo "Item added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Inventory Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../indexpic/preview.jpg');
            /* Replace with your image path */
            background-size: cover;
            /* Make the image cover the whole background */
            background-repeat: no-repeat;
            /* Prevent image repetition */
            background-position: center;
            /* Center the image */
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #EC6D10;
            padding: 15px;
            color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100%;
            z-index: 1000;
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

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {

            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Form Styling */
        .form-container {
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: white;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: 1px solid rgba(186, 60, 2, 0.8);
            outline: none;
        }

        /* Button Styling */
        .btn {
            padding: 10px 20px;
            background: #EC6D10;

            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
            margin-right: 10px;
        }

        .btn:hover {
            background: #ff8c42;

        }

        .add-item-btn {
            padding: 10px 20px;
            background: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
        }

        .add-item-btn:hover {
            background: rgba(211, 47, 47, 0.90);

        }

        .button-group {
            margin-top: 30px;
            text-align: center;
        }

        /* Success Message */
        .success-message {
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2><img src="../imgmain/logo1.png" alt="POS Logo"></h2>
        <ul>
            <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
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
    <div class="main-content">
        <div class="container">
            <h2>Add New Inventory Item</h2>

            <div class="form-container">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['item_name'];
                    $category = $_POST['category'];
                    $unit = $_POST['unit'];
                    $stock = (int)$_POST['stock'];
                    $reorder = (int)$_POST['reorder_level'];
                    $cost = $_POST['cost_price'];
                    $supplier = $_POST['supplier_id'];

                    $stmt = $pdo->prepare("INSERT INTO inventory_items (item_name, category, unit, current_stock, reorder_level, cost_price, supplier_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $category, $unit, $stock, $reorder, $cost, $supplier]);

                    echo '<p class="success-message">Item added successfully!</p>';
                }
                ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" id="item_name" name="item_name" placeholder="Enter item name" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" placeholder="Enter category">
                    </div>

                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" id="unit" name="unit" placeholder="e.g. kg, pcs, liters" required>
                    </div>

                    <div class="form-group">
                        <label for="stock">Initial Stock</label>
                        <input type="number" id="stock" name="stock" step="1" min="0" placeholder="Enter initial stock" required>
                    </div>

                    <div class="form-group">
                        <label for="reorder_level">Reorder Level</label>
                        <input type="number" id="reorder_level" name="reorder_level" step="1" min="0" placeholder="Enter reorder level" required>
                    </div>

                    <div class="form-group">
                        <label for="cost_price">Cost Price</label>
                        <input type="number" id="cost_price" name="cost_price" step="1" min="0" placeholder="Enter cost price" required>
                    </div>
                </form>

                <div class="form-group">
                    <label for="supplier_id">Supplier ID (optional)</label>
                    <input type="number" id="supplier_id" name="supplier_id" placeholder="Enter supplier ID">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn">Add Item</button>
                    <a href="view_inventory.php" class="add-item-btn">Back to Inventory</a>
                </div>
                </form>
            </div>
        </div>
</body>

</html>