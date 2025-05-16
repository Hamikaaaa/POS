<?php
include_once(__DIR__ . '/../db.php');

$search = $_GET['search'] ?? '';

// Handle delete request
if (isset($_GET['delete_item_id'])) {
    $item_id = $_GET['delete_item_id'];

    // Prepare delete query
    $delete_query = $pdo->prepare("DELETE FROM inventory_items WHERE id = ?");
    if ($delete_query->execute([$item_id])) {
        echo "<script>alert('Item deleted successfully.'); window.location.href = 'view_inventory.php';</script>";
    } else {
        echo "<script>alert('Failed to delete item. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inventory List</title>
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
            min-height: 100vh;
        }

        h2 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Buttons Styling */
        .buttons {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn,
        .add-item-btn {
            padding: 10px 15px;
            background-color: rgba(236, 109, 16);
            /* Original background color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            border: none;
        }

        .btn:hover,
        .add-item-btn:hover {
            background: rgba(236, 109, 16, 0.90);
            /* Darker shade of the original color */
        }


        /* Search Form */
        .search-form {
            margin: 20px 0;
            text-align: center;
        }

        .search-form input[type="text"] {
            padding: 10px 15px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-form button {
            padding: 10px 15px;
            background-color: rgba(236, 109, 16);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .search-form button:hover {
            background: rgba(236, 109, 16, 0.90);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            margin-left: 20px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background: #EC6D10;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Actions */
        .actions a {
            text-decoration: none;
            padding: 8px 12px;
            margin: 2px;
            display: inline-block;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }

        .update-btn {
            background-color: rgba(255, 149, 0, 0.86);
            ;
        }

        /* Action Buttons */
        button,
        a {
            transition: background-color 0.3s ease;
        }

        /* Button Hover */
        button:hover,
        a:hover {
            background-color: #BA3C02;
            /* Darker shade for hover */
            color: white;
            /* Ensure text remains white */
        }


        .delete-btn {
            background-color: #d32f2f;
            ;
        }

        .delete-btn:hover {
            color: #d32f2f;
            ;
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
            <li><a href="/POS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Inventory List</h2>

        <div class="buttons">
            <a href="addinventory_item.php" class="add-item-btn">Add New Item</a>
        </div>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by item name or category" value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
            <button type="submit">Search</button>
        </form>

        <table>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Stock</th>
                <th>Reorder Level</th>
                <th>Cost</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($search) {
                $stmt = $pdo->prepare("SELECT * FROM inventory_items WHERE item_name LIKE ? OR category LIKE ?");
                $stmt->execute(["%$search%", "%$search%"]);
            } else {
                $stmt = $pdo->query("SELECT * FROM inventory_items");
            }

            while ($row = $stmt->fetch()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['unit']) ?></td>
                    <td><?= htmlspecialchars($row['current_stock']) ?></td>
                    <td><?= htmlspecialchars($row['reorder_level']) ?></td>
                    <td>₱<?= htmlspecialchars($row['cost_price']) ?></td>
                    <td class="actions">
                        <a href="update_stock.php?item_id=<?= $row['id'] ?>" class="update-btn">Update Stock</a>
                        <a href="?delete_item_id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>