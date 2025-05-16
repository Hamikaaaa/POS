<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header('Location: /POS/index.php');
    exit();
}

try {
    $paymentsQuery = $pdo->query("SELECT p.id, p.order_id, p.payment_method, p.amount, p.created_at 
                                  FROM payments p 
                                  ORDER BY p.created_at DESC");
    $payments = $paymentsQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Super Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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


        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;

            min-height: 100vh;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        /* Table Styling */
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            margin-left: 20px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .payments-table th {
            background: #EC6D10;
            color: white;
            padding: 12px;
            text-align: center;
        }

        .payments-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .payments-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Button Styling */
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: #11998e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 20px;
            border: none;
            cursor: pointer;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-back:hover {
            background: #38ef7d;
        }

        .text-center {
            text-align: center;
        }

        .payments-table tr {
            background-color: transparent !important;
        }

        .payments-table td {
            background-color: transparent !important;
            color: white;
            /* Optional: if your background is dark */
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* Optional: subtle white border */
        }

        /* Optional: transparent header row but keep color readable */
        .payments-table th {
            background-color: rgba(236, 109, 16, 0.8);
            /* Slightly transparent */
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
            <li><a href="/POS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>


    <div class="main-content">
        <h1>Payments</h1>

        <table class="payments-table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= htmlspecialchars($payment['id']) ?></td>
                        <td><?= htmlspecialchars($payment['order_id']) ?></td>
                        <td><?= htmlspecialchars($payment['payment_method']) ?></td>
                        <td>$<?= number_format($payment['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($payment['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>