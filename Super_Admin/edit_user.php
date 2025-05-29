<?php
session_start();
require __DIR__ . '/../db.php';

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}
// If form is submitted, update the user's details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
}

// Check if user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: users.php'); // Redirect if no ID is provided
    exit();
}

$userId = $_GET['id'];

// Validate the user ID (ensure it's a valid integer)
if (!filter_var($userId, FILTER_VALIDATE_INT)) {
    header('Location: users.php'); // Redirect if invalid user ID
    exit();
}

try {
    // Fetch the user details from the database
    $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // If no user found, redirect to users page
        header('Location: users.php');
        exit();
    }

    // If form is submitted, update the user's details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];

        // Validate the inputs
        if (empty($username) || empty($email) || !in_array($role, ['admin', 'cashier', 'super_admin'])) {
            $error = 'Please fill in all fields correctly.';
        } else {
            // Update the user details in the database
            $updateStmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
            $updateStmt->bindParam(':username', $username);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':role', $role);
            $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                header('Location: users.php?message=User updated successfully');
                exit();
            } else {
                $error = 'Failed to update user details.';
            }
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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

        /* Main Content Styling */
        .main-content {
            flex-grow: 1;
            margin-left: 250px;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 15px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
            background-color: white;
            color: #333;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 10px;
        }

        /* Button Styling */
        .btn {
            padding: 12px 20px;
            background: rgba(186, 60, 2, 1);
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
            text-align: center;
            border: none;
        }

        .btn:hover {
            background-color: rgba(186, 60, 2, 0.7);
        }

        a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: white;
            transition: color 0.3s;
            text-align: center;
        }

        a:hover {
            color: rgba(255, 255, 255, 0.7);
            /* Darken the white color to a lighter shade */
        }


        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: white;
            font-size: 15px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
            background-color: white;
            color: #333;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }
    </style>

    </style>

</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>
            <img src="../imgmain/logo1.png" alt="image">
        </h2>
        <ul>
            <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="categories.php"><i class="fas fa-list"></i> Categories</a></li>
            <li><a href="products.php" class="active"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="sales.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
            <li><a href="inventory.php"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="suppliers.php"><i class=" fas fa-address-card"></i>Suppliers</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1>Edit User</h1>

            <!-- Error or Success Message -->
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="edit_user.php?id=<?= $user['id']; ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password <small>(leave blank to keep current password)</small></label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Enter new password">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="cashier" <?= $user['role'] === 'cashier' ? 'selected' : ''; ?>>Cashier</option>
                        <option value="super_admin" <?= $user['role'] === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn">Update User</button>
            </form>


            <!-- Back Button -->
            <a href="users.php">Back to Manage Users</a>
        </div>
    </div>


</body>
<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.password-toggle');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

</html>