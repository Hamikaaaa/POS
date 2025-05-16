<?php
require __DIR__ . '/../db.php'; // Ensure correct database connection

// Fetch products with category name
$sql = "SELECT products.*, categories.name AS category_name 
            FROM products 
            JOIN categories ON products.category_code = categories.category_code";

try {
    $result = $pdo->query($sql);
} catch (PDOException $e) {
    die("SQL Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product List</title>
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
            margin-left: 20px;
            margin-bottom: 20px;
        }

        /* Buttons Styling */
        .buttons {
            margin-bottom: 20px;
            text-align: center;
            /* Aligns buttons to the left */
        }

        .btn,
        .filter-btn {
            width: 150px;
            /* Set fixed width */
            padding: 10px 15px;
            background-color: #EC6D10;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
            margin-left: 20px;
            cursor: pointer;
            border: none;
            text-align: center;
        }


        .btn:hover,
        .filter-btn:hover {
            background-color: rgba(255, 111, 0, 0.86);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;

            overflow: hidden;
            margin-left: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            color: white;
        }

        th {
            background: #EC6D10;
            color: white;
        }

        td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            margin: 2px;
            display: inline-block;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }

        .edit-btn {
            background-color: rgba(255, 149, 0, 0.86);
        }

        .edit-btn:hover {
            background-color: rgba(255, 111, 0, 0.86);
        }

        .delete-btn {
            background-color: #d9534f;
        }

        .delete-btn:hover {
            background-color: #c9302c;
        }
    </style>
    <script>
        function filterCategory(category) {
            let rows = document.querySelectorAll('.product-row');
            rows.forEach(row => {
                let cat = row.getAttribute('data-category');
                if (category === 'All' || cat.toLowerCase().includes(category.toLowerCase())) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
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
        <h2>Product List</h2>

        <div class="buttons">
            <a href="add_product.php" class="btn">Add New Product</a>
        </div>

        <!-- Filter Buttons -->
        <div class="buttons">
            <button class="filter-btn" onclick="filterCategory('All')">All</button>
            <button class="filter-btn" onclick="filterCategory('Rice')">Rice</button>
            <button class="filter-btn" onclick="filterCategory('Viands')">Viands</button>
            <button class="filter-btn" onclick="filterCategory('Beverages')">Beverages</button>
            <button class="filter-btn" onclick="filterCategory('Desserts')">Desserts</button>
        </div>

        <!-- Products Table -->
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Barcode</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <tr class="product-row" data-category="<?= htmlspecialchars($row['category_name']); ?>">
                    <td><img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="Product"></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['description']); ?></td>
                    <td>₱<?= htmlspecialchars($row['price']); ?></td>
                    <td><?= htmlspecialchars($row['stock']); ?></td>
                    <td><?= htmlspecialchars($row['barcode']); ?></td>
                    <td><?= htmlspecialchars($row['category_name']); ?></td>
                    <td class="actions">
                        <a href="edit_product.php?id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="deleteprod.php?id=<?= $row['id']; ?>" class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>


    </div>
</body>

</html>