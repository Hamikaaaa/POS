<?php
session_start();
require __DIR__ . '/../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = trim($_POST['category_name']);

    if (!empty($categoryName)) {
        $categoryCode = strtoupper(substr($categoryName, 0, 3));

        try {
            $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_code = :code");
            $stmt->execute(['code' => $categoryCode]);

            if ($stmt->rowCount() > 0) {
                $categoryCode .= rand(100, 999);
            }

            $stmt = $pdo->prepare("INSERT INTO categories (category_code, name) VALUES (:code, :name)");
            $stmt->execute(['code' => $categoryCode, 'name' => $categoryName]);

            header("Location: categories.php");
            exit();
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } else {
        echo "Category name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category - SukariKo!</title>
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

        /* Form Container */
        .form-container {
            margin-left: 270px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-box {

            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-box h1 {
            text-align: center;
            color: #D44606;
            margin-bottom: 20px;
        }

        .form-box label {
            font-weight: bold;
            color: white;
            display: block;
            margin-bottom: 8px;
        }

        .form-box input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .form-actions button,
        .form-actions a {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .form-actions button {
            background-color: rgba(255, 149, 0, 0.86);
            /* Regular button color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            /* Smooth transition for color change */
        }

        .form-actions button:hover {
            background-color: rgba(255, 149, 20, 0.70);
            /* Darker color on hover */
        }


        .form-actions a {
            background-color: rgba(186, 60, 2, 1);

            color: white;
        }

        .form-actions a:hover {
            background-color: rgba(186, 60, 2, 0.7);

        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>
            <img src="../imgmain/logo1.png" alt="SukariKo Logo">
        </h2>
        <ul>
            <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
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


    <div class="form-container">
        <div class="form-box">
            <h1>Add New Category</h1>
            <form method="post">
                <label for="category_name">Category Name:</label>
                <input type="text" name="category_name" id="category_name" required>

                <div class="form-actions">
                    <button type="submit">Add Category</button>
                    <a href="products.php">Back to Products</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>