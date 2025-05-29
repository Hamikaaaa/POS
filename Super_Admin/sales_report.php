<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header('Location: index.php');
    exit();
}
// Get sales data
try {
    // Monthly sales - last 12 months
    $monthlySales = $pdo->query("
        SELECT DATE_FORMAT(o.created_at, '%Y-%m') as month, 
               SUM(o.total_amount) as total_sales
        FROM orders o
        GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
        ORDER BY month DESC
        LIMIT 12
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Recent sales - last 10
    $recentSales = $pdo->query("
        SELECT o.id, o.total_amount, o.created_at, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Daily sales for the last 30 days
$dailySales = $pdo->query("
    SELECT DATE(created_at) as day, 
           SUM(total_amount) as total_sales
    FROM orders
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            margin-left: 250px;
            padding: 25px;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 15px;
            background-color: #EC6D10;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #D44606;
        }

        .back-btn i {
            margin-right: 8px;
        }

        /* Page Header */
        .page-header {
            margin-left: 40px;
            margin-bottom: 25px;
            color: white;
            border-bottom: 2px solid #EC6D10;
            padding-bottom: 10px;
        }

        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-left: 40px;
            margin-bottom: 25px;
        }

        .card h2 {
            margin-left: 40px;
            margin-top: 0;
            color: #EC6D10;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background-color: #EC6D10;
            color: white;
            font-weight: 600;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tr:hover {
            background-color: #f5f5f5;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
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
            <li><a href="users.php"><i class="fas fa-users"></i>Manage Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">


        <h1 class="page-header">Sales Report</h1>

        <div class="card">
            <h2>Monthly Sales</h2>
            <div class="chart-container">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h2>Daily Sales (Last 30 Days)</h2>
            <div class="chart-container">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Recent Transactions</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Account</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentSales as $sale): ?>
                        <tr>
                            <td><?= $sale['id'] ?></td>
                            <td><?= htmlspecialchars($sale['username']) ?></td>
                            <td>₱<?= number_format($sale['total_amount'], 2) ?></td>
                            <td><?= date('M j, Y g:i A', strtotime($sale['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Daily Sales Chart
        const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($dailySales, 'day')) ?>,
                datasets: [{
                    label: 'Daily Sales (₱)',
                    data: <?= json_encode(array_column($dailySales, 'total_sales')) ?>,
                    backgroundColor: 'rgba(236, 109, 16, 0.2)',
                    borderColor: '#EC6D10',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        // Monthly Sales Chart
        const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($monthlySales, 'month')) ?>,
                datasets: [{
                    label: 'Sales (₱)',
                    data: <?= json_encode(array_column($monthlySales, 'total_sales')) ?>,
                    backgroundColor: '#EC6D10',
                    borderColor: '#D44606',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>