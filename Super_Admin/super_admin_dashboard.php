<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header('Location: /POS/index.php');
    exit();
}

try {
    // Original dashboard metrics
    $outOfStockQuery = $pdo->query("SELECT COUNT(*) AS out_of_stock FROM products WHERE stock = 0");
    $outOfStock = $outOfStockQuery->fetch(PDO::FETCH_ASSOC)['out_of_stock'] ?? 0;

    $categoryCountQuery = $pdo->query("SELECT COUNT(*) AS category_count FROM categories");
    $categoryCount = $categoryCountQuery->fetch(PDO::FETCH_ASSOC)['category_count'] ?? 0;

    $paymentTypeQuery = $pdo->query("SELECT COUNT(DISTINCT payment_method) AS payment_types FROM payments");
    $paymentTypes = $paymentTypeQuery->fetch(PDO::FETCH_ASSOC)['payment_types'] ?? 0;

    $adminAccountQuery = $pdo->query("SELECT COUNT(*) AS admin_accounts FROM users WHERE role = 'admin'");
    $adminAccounts = $adminAccountQuery->fetch(PDO::FETCH_ASSOC)['admin_accounts'] ?? 0;

    $customerAccountQuery = $pdo->query("SELECT COUNT(*) AS customer_accounts FROM users WHERE role = 'user'");
    $customerAccounts = $customerAccountQuery->fetch(PDO::FETCH_ASSOC)['customer_accounts'] ?? 0;

    // Sales data
    $dailySalesQuery = $pdo->query("SELECT SUM(total_amount) AS total_sales FROM orders WHERE DATE(created_at) = CURDATE()");
    $dailySales = $dailySalesQuery->fetch(PDO::FETCH_ASSOC)['total_sales'] ?? 0;

    // Get out of stock products list
    $outOfStockProducts = $pdo->query("
        SELECT p.id, p.name, p.stock, c.name as category 
        FROM products p
        LEFT JOIN categories c ON p.category_code = c.category_code
        WHERE p.stock = 0
        ORDER BY p.name
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Current vs last month comparison
    $currentMonthSales = $pdo->query("
        SELECT SUM(total_amount) as total 
        FROM orders 
        WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
        AND YEAR(created_at) = YEAR(CURRENT_DATE())
    ")->fetch(PDO::FETCH_ASSOC);

    $lastMonthSales = $pdo->query("
        SELECT SUM(total_amount) as total 
        FROM orders 
        WHERE MONTH(created_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
        AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
    ")->fetch(PDO::FETCH_ASSOC);
    $monthlyComparison = $pdo->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS month,
        SUM(total_amount) AS total_sales
    FROM orders
    WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 2 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month DESC
    LIMIT 2
  ")->fetchAll(PDO::FETCH_ASSOC);


    $topProducts = $pdo->query("
  SELECT 
      p.name,
      SUM(od.quantity) AS total_quantity,
      SUM(od.price * od.quantity) AS total_sales
  FROM order_details od
  JOIN products p ON od.product_id = p.id
  JOIN orders o ON od.order_id = o.id
  WHERE MONTH(o.created_at) = MONTH(CURRENT_DATE())
  AND YEAR(o.created_at) = YEAR(CURRENT_DATE())
  GROUP BY p.name
  ORDER BY total_quantity DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: "  . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
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

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .main-content h1 {
            color: white;
        }


        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background: linear-gradient(to right, rgb(254, 123, 0), rgb(186, 60, 2));
            padding: 20px;
            border-radius: 8px;
            color: white;
            text-align: center;
            flex: 1;
            min-width: 200px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .chart-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .chart-container {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }



        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }



        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 40%;
            text-align: center;
        }

        .close {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }

        .sales-link {
            text-decoration: none;
            color: inherit;
            display: block;
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
        <h1>Super Admin Dashboard</h1>

        <div class="dashboard-container">
            <div class="card" onclick="openModal('Products (Out of Stock)', 'These are products that need restocking.', '<?php echo $outOfStock; ?>')">
                <h3>Products (Out of Stock)</h3>
                <p>Items: <?php echo $outOfStock; ?></p>
            </div>

            <div class="card" onclick="openModal('Category Count', 'Total number of categories.', '<?php echo $categoryCount; ?>')">
                <h3>Category Count</h3>
                <p><?php echo $categoryCount; ?></p>
            </div>

            <div class="card" onclick="openModal('Payment Types', 'Total unique payment methods used.', '<?php echo $paymentTypes; ?>')">
                <h3>Payment Types</h3>
                <p><?php echo $paymentTypes; ?></p>
            </div>

            <div class="card" onclick="openModal('Admin Accounts', 'Total number of admin users.', '<?php echo $adminAccounts; ?>')">
                <h3>Admin Accounts</h3>
                <p><?php echo $adminAccounts; ?></p>
            </div>

            <div class="card" onclick="openModal('Pending Requests', 'Total number of pending admin approvals.', '0')">
                <h3>Pending Requests</h3>
                <p>0</p>
            </div>


            <div class="card">
                <h3>Today's Sales</h3>
                <p>₱<?php echo number_format($dailySales, 2); ?></p>
            </div>

            <div class="card" onclick="openModal('Request Success', 'Total successfully processed requests.', '0')">
                <h3>Request Success</h3>
                <p>0</p>
            </div>

            <div class="card" onclick="openModal('Customer Accounts', 'Total number of registered customers.', '<?php echo $customerAccounts; ?>')">
                <h3>Customer Accounts</h3>
                <p><?php echo $customerAccounts; ?></p>
            </div>
        </div>

        <div class="chart-row">
            <div class="chart-container">
                <h3>Monthly Sales Comparison</h3>
                <canvas id="monthlySalesChart"></canvas>
            </div>

            <div class="chart-container">
                <h3>Top Selling Products This Month</h3>
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <!-- MODAL STRUCTURE -->
        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
                <p><strong>Total:</strong> <span id="modalValue"></span></p>
                <div id="outOfStockProducts" style="display: none; margin-top: 20px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #D44606; color: white;">
                                <th style="padding: 10px;">Product</th>
                                <th style="padding: 10px;">Category</th>
                                <th style="padding: 10px;">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($outOfStockProducts as $product): ?>
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 10px;"><?= htmlspecialchars($product['name']) ?></td>
                                    <td style="padding: 10px;"><?= htmlspecialchars($product['category'] ?? 'Uncategorized') ?></td>
                                    <td style="padding: 10px; color: red; font-weight: bold;"><?= $product['stock'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            function openModal(title, description, value) {
                document.getElementById('modalTitle').innerText = title;
                document.getElementById('modalDescription').innerText = description;
                document.getElementById('modalValue').innerText = value;

                const outOfStockDiv = document.getElementById('outOfStockProducts');
                if (title.includes('Out of Stock') && <?= !empty($outOfStockProducts) ? 'true' : 'false' ?>) {
                    outOfStockDiv.style.display = 'block';
                } else {
                    outOfStockDiv.style.display = 'none';
                }

                document.getElementById('infoModal').style.display = 'flex';
            }

            function closeModal() {
                document.getElementById('infoModal').style.display = 'none';
            }
            // Monthly Sales Comparison Chart
            const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');

            // Prepare data for monthly comparison
            const monthlyLabels = [];
            const monthlyData = [];

            <?php
            // Process monthly comparison data for chart
            foreach (array_reverse($monthlyComparison) as $month) {
                $monthName = date('F Y', strtotime($month['month']));
                echo "monthlyLabels.push('" . $monthName . "');";
                echo "monthlyData.push(" . $month['total_sales'] . ");";
            }
            ?>

            new Chart(monthlySalesCtx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Sales (₱)',
                        data: monthlyData,
                        backgroundColor: [
                            'rgba(236, 109, 16, 0.7)',
                            'rgba(16, 109, 236, 0.7)'
                        ],
                        borderColor: [
                            'rgba(212, 70, 6, 1)',
                            'rgba(6, 70, 212, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: ₱' + context.raw.toLocaleString();
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Monthly Sales Comparison',
                            font: {
                                size: 16
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
                            }
                        }
                    }
                }
            });

            // Top Selling Products Chart
            const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');

            // Prepare data for top products
            const productLabels = <?php echo json_encode(array_column($topProducts, 'name')); ?>;
            const productData = <?php echo json_encode(array_column($topProducts, 'total_quantity')); ?>;
            const productSales = <?php echo json_encode(array_column($topProducts, 'total_sales')); ?>;

            // Create gradient for bars
            const gradient = topProductsCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(236, 109, 16, 0.8)');
            gradient.addColorStop(1, 'rgba(236, 109, 16, 0.2)');

            new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: productLabels,
                    datasets: [{
                        label: 'Quantity Sold',
                        data: productData,
                        backgroundColor: gradient,
                        borderColor: 'rgba(212, 70, 6, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    return [
                                        'Quantity: ' + context.raw,
                                        'Sales: ₱' + productSales[index].toLocaleString()
                                    ];
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Top Selling Products This Month',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantity Sold'
                            }
                        },
                        y: {
                            ticks: {
                                autoSkip: false
                            }
                        }
                    }
                }
            });
        </script>
</body>

</html>