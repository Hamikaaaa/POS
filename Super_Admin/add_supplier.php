<?php
session_start();
require __DIR__ . '/../db.php'; // Corrected to use __DIR__

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = trim($_POST['name']);
    $contact_person = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $products_supplied = trim($_POST['products_supplied']);
    $status = trim($_POST['status']);

    try {
        // Prepare SQL statement to insert supplier data
        $stmt = $pdo->prepare("INSERT INTO suppliers (name, contact_person, phone, email, products_supplied, status) 
                               VALUES (:name, :contact_person, :phone, :email, :products_supplied, :status)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':contact_person', $contact_person);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':products_supplied', $products_supplied);
        $stmt->bindParam(':status', $status);

        // Execute the query
        if ($stmt->execute()) {
            echo "<p>Supplier added successfully!</p>";
        } else {
            echo "<p>Failed to add supplier. Please try again.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Database error: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Supplier</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../indexpic/preview.jpg') no-repeat center center fixed;
            /* Add your background image */
            background-size: cover;
            /* Ensure the image covers the full background */
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            /* Ensures content stays aligned to the top */
        }

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

        .main-content {
            max-width: 600px;
            margin: auto;
            align-items: center;
            padding: 20px;
            border-radius: 8px;
            margin-right: 500px;

        }

        h1 {
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
            font-size: 16px;
            color: white;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        /* General button styling */
        /* General button styling */
        button,
        .btn {
            padding: 12px 24px;
            /* Same padding for both buttons */
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-align: center;
            min-width: 150px;
            /* Ensure both buttons are the same width */
            display: inline-block;
            text-decoration: none;
            /* Remove underline */
            width: 100%;
            /* Make both buttons expand to fill available space equally */
        }

        /* Add button styling */
        .add-btn {
            background-color: #EC6D10;
            color: white;
        }

        .add-btn:hover {
            background-color: rgba(236, 109, 16, 0.80);


        }

        /* Back button styling */
        .back-btn {
            background-color: #EC6D10;
            color: white;
            /* Change text color to white */
        }


        .back-btn:hover {
            background-color: rgba(236, 109, 16, 0.80);


        }

        /* Ensure buttons inside the button group are spaced out evenly */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            justify-content: center;
            width: 100%;
            /* Make sure the group expands to full width */
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
            <li><a href="suppliers.php"><i class="fas fa-users"></i> Suppliers</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Add Supplier</h1>

        <form action="add_supplier.php" method="POST">
            <label for="name">Supplier Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="contact_person">Contact Person:</label>
            <input type="text" name="contact_person" id="contact_person" required>

            <label for="phone">Phone:</label>
            <input type="tel" name="phone" id="phone" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="products_supplied">Products Supplied:</label>
            <textarea name="products_supplied" id="products_supplied" rows="4" required></textarea>



        </form>

        <!-- Link back to the supplier list page -->
        <div class="button-group">
            <button type="submit" class="btn add-btn">Add Supplier</button>
            <a href="suppliers.php" class="btn back-btn">Back to Supplier</a>
        </div>
    </div>
</body>

</html>