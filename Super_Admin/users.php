<?php
session_start();
require __DIR__ . '/../db.php';

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}

// Get the filter parameter (defaults to 'all' if not set)
$filter = $_GET['filter'] ?? 'all';

try {
    // Prepare SQL based on the filter for roles
    if ($filter === 'employees') {
        // Filter: Users with role 'admin' (Employees)
        $usersQuery = $pdo->query("SELECT id, username, role, email, created_at FROM users WHERE role = 'admin' ORDER BY created_at DESC");
    } elseif ($filter === 'customers') {
        // Filter: Users with role 'user' (Customers)
        $usersQuery = $pdo->query("SELECT id, username, role, email, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC");
    } else {
        // All users: No filter applied
        $usersQuery = $pdo->query("SELECT id, username, role, email, created_at FROM users ORDER BY created_at DESC");
    }

    $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users - Super Admin</title>
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
            max-width: 900px;
            margin: 20px auto;
            margin-left: 400px;
            /* Horizontally center and add vertical margin */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }


        h1 {
            text-align: center;
            color: white;
            /* Dark Teal */
            margin-bottom: 20px;
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


        .action-buttons {
            text-align: center;
        }

        /* Buttons Styling */
        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
            margin: 5px;
        }

        .edit-btn {
            background: #fbc02d;
            /* Yellow-Gold */
            color: black;
        }

        .edit-btn:hover {
            background: #e0a800;
        }

        .delete-btn {
            background: #d32f2f;
            /* Dark Red */
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .button-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            /* optional spacing */
        }

        .add-user {
            background-color: rgba(186, 60, 2, 1);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s;
            margin-bottom: 25px;
        }

        .add-user:hover {
            background-color: rgba(150, 40, 0, 1);
            /* slightly darker on hover */
        }


        .back-btn {
            background-color: rgba(255, 111, 0);

            /* Dark Teal */
            color: white;
            display: inline-block;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: rgba(255, 111, 0, 0.90);
        }

        /* Filter Buttons Styling */
        .filter-buttons {
            margin-bottom: 20px;
        }

        .filter-btn {
            padding: 10px 15px;
            font-size: 14px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            background: #EC6D10;
            border: 1px solid #ccc;
            transition: background 0.3s;

            width: 120px;
            /* Fixed width for consistency */
            box-sizing: border-box;
            text-align: center;
            /* Ensures text stays centered */
        }


        .filter-btn:hover {
            background: #fbc02d;
        }

        .active-filter {
            background: #EC6D10;
            color: white;
        }

        /* Filter Buttons Styling */
        .filter-buttons {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            /* Center the buttons */
            gap: 10px;
            /* Reduce space between buttons */
        }

        .filter-btn {
            padding: 8px 12px;
            /* Smaller padding for smaller buttons */
            font-size: 14px;
            /* Smaller text size */
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            background: #EC6D10;
            /* Default background color */
            border: 2px solid #EC6D10;
            /* Border color to match default background */
            transition: background 0.3s, transform 0.2s ease-in-out;
            /* Smooth transition for hover and click */
            text-transform: uppercase;
            text-decoration: none;
            /* Remove underline */
            color: white;
            /* Default text color */
        }

        .filter-btn:hover {
            background: #c75e07;
            /* Darker shade of the default color for hover */
            color: white;
            transform: scale(1.05);
            /* Slightly grow the button */
        }

        .filter-btn:focus {
            outline: none;
        }

        .active-filter {
            background: #EC6D10;
            /* Keep the default color */
            color: white;
            border-color: #EC6D10;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            /* Optional subtle shadow */
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
        <h1>Manage Users</h1>
        <div class="button-wrapper">
            <a href="add_user.php" class="btn add-user">Add New User</a>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <a href="?filter=all" class="filter-btn <?= $filter === 'all' ? 'active-filter' : ''; ?>">All</a>
            <a href="?filter=employees" class="filter-btn <?= $filter === 'employees' ? 'active-filter' : ''; ?>">Employees</a>
            <a href="?filter=customers" class="filter-btn <?= $filter === 'customers' ? 'active-filter' : ''; ?>">Customers</a>
        </div>


        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Date Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= ucfirst(htmlspecialchars($user['role'])); ?></td>
                        <td><?= htmlspecialchars($user['created_at']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn edit-btn">Edit</a>
                            <a href="delete_user.php?id=<?= $user['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>
</body>

</html>