<?php
session_start();
require __DIR__ . '/../db.php';

// Check if the user is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header('Location: /POS/index.php');
    exit();
}

// Fetch categories from the database
try {
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
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
            /* Same as sidebar width */
            padding: 30px;

            min-height: 100vh;
        }

        h1 {
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Centering Buttons */
        .btn-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        /* Buttons Styling */
        .btn {
            padding: 10px 15px;
            background-color: #EC6D10;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            font-weight: bold;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: rgba(255, 111, 0, 0.86);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent;
            /* Set table background to transparent */
            border: none;
            /* Remove the default table border */
            margin-top: 20px;
            margin-left: 20px;
        }

        th,
        td {

            padding: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.5);
            /* Light border to make it visible */
            color: #f0f0f0;
            /* Light gray text color */
        }

        th {
            background: #EC6D10;
            color: white;
        }


        /* Edit and Delete Button Styles */
        .btn.edit {
            background: rgba(255, 149, 0, 0.86);
            color: black;
        }

        .btn.edit:hover {
            background: #e0a800;
        }

        .btn.delete {
            background: #d32f2f;
            color: white;
        }

        .btn.delete:hover {
            background: #b71c1c;
        }

        /* Extra space before the Back to Dashboard button */
        .back-btn-container {
            margin-top: 30px;
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
        <h1>Manage Categories</h1>

        <!-- Centered Add New Category Button -->
        <div class="btn-container">
            <a href="add_category.php" class="btn">Add New Category</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['id']); ?></td>
                        <td><?= htmlspecialchars($category['name']); ?></td>
                        <td>
                            <a href="edit_category.php?id=<?= $category['id']; ?>" class="btn edit">Edit</a>
                            <a href="delete_category.php?id=<?= $category['id']; ?>" class="btn delete" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>
</body>

</html>