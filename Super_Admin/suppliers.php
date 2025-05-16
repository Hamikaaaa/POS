<?php
session_start();
require __DIR__ . '/../db.php';

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}

try {
    // Fetch suppliers from database
    $suppliersQuery = $pdo->query("SELECT id, name, contact_person, phone, email, products_supplied, status, created_at FROM suppliers ORDER BY created_at DESC");
    $suppliers = $suppliersQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Suppliers - Super Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../indexpic/preview.jpg') no-repeat center center fixed;
            /* Add your background image */
            background-size: cover;
            /* Ensure the image covers the full background */
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            /* Ensures content stays aligned to the top */
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

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .container {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
        }

        h1 {
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            background: #EC6D10;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s, transform 0.2s;
            font-weight: bold;

            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #d95d08;
            transform: translateY(-2px);
        }



        .add-supplier {
            padding: 10px 20px;
            border-radius: 6px;
            background-color: #EC6D10;
            transition: background 0.3s, transform 0.2s;
            color: white;

            border: none;
            cursor: pointer;
            text-align: center;
            text-decoration: none;

        }

        .add-supplier:hover {
            background-color: #d95d08;
        }

        .filter-buttons {
            margin-bottom: 20px;
            text-align: center;
        }

        .filter-btn {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
            padding: 8px 16px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-btn:hover {
            background-color: #e9ecef;
        }

        .active-filter {
            background-color: #EC6D10;
            color: white;
            border-color: #EC6D10;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            margin-left: 15px;
            background-color: transparent;
        }

        thead th {
            background-color: #EC6D10;
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        thead th:last-child {
            border-right: none;
        }

        table tbody tr {
            transition: background-color 0.3s ease;
        }

        table td {
            padding: 12px;
            color: white;
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        table td:last-child {
            border-right: none;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .edit-btn,
        .delete-btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background-color: #ffc107;
            color: #000;
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
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
            <li><a href="suppliers.php"><i class="fas fa-address-card"></i> Suppliers</a></li>

            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="/POS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>


    <div class="container">
        <h1>Manage Suppliers</h1>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="add_supplier.php" class="add-supplier">Add New Supplier</a>
        </div>


        <table>
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Products Supplied</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?= htmlspecialchars($supplier['id']); ?></td>
                        <td><?= htmlspecialchars($supplier['name']); ?></td>
                        <td><?= htmlspecialchars($supplier['contact_person']); ?></td>
                        <td><?= htmlspecialchars($supplier['phone']); ?></td>
                        <td><?= htmlspecialchars($supplier['email']); ?></td>
                        <td><?= htmlspecialchars($supplier['products_supplied']); ?></td>
                        <td class="action-buttons" style="align-items: center;">
                            <a href="edit_supplier.php?id=<?= $supplier['id']; ?>" class="btn edit-btn">Edit</a>
                            <a href="delete_supplier.php?id=<?= $supplier['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>