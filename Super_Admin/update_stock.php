<?php
session_start();
require __DIR__ . '/../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if form is submitted to update inventory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_inventory'])) {
    $item_id = $_POST['item_id'];
    $new_stock = $_POST['current_stock'];
    $new_reorder_level = $_POST['reorder_level'];

    // Validate input
    if (empty($item_id) || empty($new_stock) || empty($new_reorder_level)) {
        $_SESSION['message'] = "Item ID, stock, and reorder level are required!";
        header("Location: view_inventory.php");
        exit();
    }

    // Update the inventory stock in the database
    $update_query = $pdo->prepare("UPDATE inventory_items SET current_stock = ?, reorder_level = ?, last_updated = NOW() WHERE id = ?");

    if ($update_query->execute([$new_stock, $new_reorder_level, $item_id])) {
        $_SESSION['message'] = "Inventory updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update inventory!";
    }
    header("Location: view_inventory.php");
    exit();
}

// Fetch inventory items
$inventory_query = $pdo->query("SELECT * FROM inventory_items");
$inventory = $inventory_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F4F6F9;
            display: flex;
            min-height: 100vh;
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
            background: white;
            min-height: 100vh;
        }

        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            margin-left: 20px;
        }

        /* Table Styling */
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .inventory-table th {
            background-color: #EC6D10;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .inventory-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .inventory-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .inventory-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Form Styling */
        .update-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .form-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-input:focus {
            outline: none;
            border-color: #EC6D10;
        }

        /* Button Styling */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #11998e;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0d7a6f;
        }

        .btn-add {
            display: inline-block;
            background-color: #EC6D10;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-add:hover {
            background-color: #d45d0a;
        }

        /* Message Styling */
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Header Styling */
        h1 {
            color: #333;
            margin-bottom: 20px;
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
            <li><a href="view_inventory.php" class="active"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="suppliers.php"><i class=" fas fa-address-card"></i>Suppliers</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content-container">
            <h1>Inventory Management</h1>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert <?php echo strpos($_SESSION['message'], 'success') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>



            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Stock</th>
                        <th>Reorder Level</th>
                        <th>Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td><?php echo htmlspecialchars($item['unit']); ?></td>
                            <td><?php echo htmlspecialchars($item['current_stock']); ?></td>
                            <td><?php echo htmlspecialchars($item['reorder_level']); ?></td>
                            <td>$<?php echo number_format($item['cost_price'], 2); ?></td>
                            <td>
                                <form method="POST" class="update-form">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="current_stock" class="form-input"
                                        value="<?php echo htmlspecialchars($item['current_stock']); ?>"
                                        placeholder="Stock" required>
                                    <input type="number" name="reorder_level" class="form-input"
                                        value="<?php echo htmlspecialchars($item['reorder_level']); ?>"
                                        placeholder="Reorder" required>
                                    <button type="submit" name="update_inventory" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update
                                    </button>

                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="add_inventory.php" class="btn-add">
                <i class="fas fa-plus"></i> Add New Inventory Item
            </a>
            <a href="view_inventory.php" class="btn-add">
                <i class="fas fa-back"></i> Back to inventory
            </a>
        </div>
    </div>
</body>

</html>