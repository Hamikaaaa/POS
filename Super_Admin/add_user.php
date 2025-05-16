<?php
session_start();
require __DIR__ . '/../db.php';

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}

// Initialize variables for form data
$username = $email = $role = $password = "";
$usernameErr = $emailErr = $roleErr = $passwordErr = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = htmlspecialchars($_POST["username"]);
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $usernameErr = "Username already exists";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = htmlspecialchars($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $emailErr = "Email already exists";
        }
    }

    // Validate role
    if (empty($_POST["role"])) {
        $roleErr = "Role is required";
    } else {
        $role = htmlspecialchars($_POST["role"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    // If no errors, insert the user into the database
    if (empty($usernameErr) && empty($emailErr) && empty($roleErr) && empty($passwordErr)) {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement
            $sql = "INSERT INTO users (username, email, role, password) VALUES (:username, :email, :role, :password)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters and execute
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':role' => $role,
                ':password' => $hashedPassword
            ]);

            // Redirect to manage users page after successful addition
            header('Location: users.php');
            exit();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
            /* Make sure the main content does not overlap the sidebar */
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;

            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: white;
            margin-bottom: 20px;
        }

        /* Form Styling */
        .form-group {
            margin: 15px 0;
            text-align: left;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }

        /* Button Styling */
        .btn {
            padding: 10px 15px;
            background: rgba(186, 60, 2, 1);
            /* Original button color */
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: rgba(150, 40, 0, 1);
            /* Darker shade on hover */
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



        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
            background-color: white;
            color: #333;

            /* optional: hides default arrow in some browsers */
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1>Add New User</h1>
            <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($username); ?>" placeholder="Username" required>
                    <span class="error"><?= $usernameErr; ?></span>
                </div>

                <div class="form-group">
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="Email" required>
                    <span class="error"><?= $emailErr; ?></span>
                </div>

                <div class="form-group">
                    <select id="role" name="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin" <?= ($role == "admin") ? "selected" : ""; ?>>Admin</option>
                        <option value="user" <?= ($role == "user") ? "selected" : ""; ?>>User</option>
                    </select>
                    <span class="error"><?= $roleErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</small></label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="password">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                </div>

                <button type="submit" class="btn">Add User</button>
            </form>
            <a href="users.php">Back to Manage Users</a>
        </div>
    </div>

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
</body>

</html>