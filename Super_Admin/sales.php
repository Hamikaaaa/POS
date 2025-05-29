<?php
session_start();
require __DIR__ . '/../db.php';

// Ensure only Super Admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: index.php');
    exit();
}

try {
    // Adjusted Query: Verify column names match your database
    $salesQuery = $pdo->query("
        SELECT s.id, p.name AS product_name, 
               s.amount, s.created_at
        FROM sales s 
        JOIN products p ON s.product_id = p.id 
        ORDER BY s.created_at DESC
    ");

    $sales = $salesQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales - Super Admin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 20px;
        }

        .main-content {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color:rgb(0, 0, 0); /* Dark Teal */
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background: #EC6D10; /* Dark Teal */
            color: white;
        }

        /* Amount Styling */
        .amount {
            font-weight: bold;
            color: #388E3C; /* Green */
        }

        /* Buttons */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #11998e; /* Green Accent */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #38ef7d; /* Light Green */
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Sales Records</h1>

        <table>
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?= htmlspecialchars($sale['id']) ?></td>
                        <td><?= htmlspecialchars($sale['product_name']) ?></td>
                        <td class="amount">$<?= number_format($sale['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($sale['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard button (moved below) -->
        <a href="super_admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>