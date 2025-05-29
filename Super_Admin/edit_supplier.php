<?php
session_start();
require __DIR__ . '/../db.php'; // Ensure the correct database connection

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}

// Fetch the supplier details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $supplier_id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM suppliers WHERE id = :id");
        $stmt->bindParam(':id', $supplier_id, PDO::PARAM_INT);
        $stmt->execute();
        $supplier = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$supplier) {
            echo "Supplier not found.";
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    echo "Invalid supplier ID.";
    exit();
}

// Update the supplier details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact_person = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $products_supplied = trim($_POST['products_supplied']);
    $status = trim($_POST['status']);

    try {
        $updateStmt = $pdo->prepare("UPDATE suppliers 
                                     SET name = :name, 
                                         contact_person = :contact_person, 
                                         phone = :phone, 
                                         email = :email, 
                                         products_supplied = :products_supplied, 
                                         status = :status 
                                     WHERE id = :id");
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':contact_person', $contact_person);
        $updateStmt->bindParam(':phone', $phone);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':products_supplied', $products_supplied);
        $updateStmt->bindParam(':status', $status);
        $updateStmt->bindParam(':id', $supplier_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header('Location: suppliers.php');
            exit();
        } else {
            echo "<p>Failed to update supplier. Please try again.</p>";
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
    <title>Edit Supplier</title>
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

            padding: 20px;
            border-radius: 8px;

        }

        h1 {
            color: white;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-size: 16px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        button {
            background-color: #EC6D10;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e55d00;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>
            <img src="../imgmain/logo1.1.png" alt="image">
        </h2>
        <ul>
            <li><a href="super_admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="categories.php"><i class="fas fa-list"></i> Categories</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="suppliers.php"><i class="fas fa-users"></i> Suppliers</a></li>
            <li><a href="view_recipes.php"><i class="fas fa-utensils"></i> Recipes</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Edit Supplier</h1>

        <form action="edit_supplier.php?id=<?= htmlspecialchars($supplier_id); ?>" method="POST">
            <label for="name">Supplier Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($supplier['name']); ?>" required>

            <label for="contact_person">Contact Person:</label>
            <input type="text" name="contact_person" id="contact_person" value="<?= htmlspecialchars($supplier['contact_person']); ?>" required>

            <label for="phone">Phone:</label>
            <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($supplier['phone']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($supplier['email']); ?>" required>

            <label for="products_supplied">Products Supplied:</label>
            <textarea name="products_supplied" id="products_supplied" rows="4" required><?= htmlspecialchars($supplier['products_supplied']); ?></textarea>


            <button type="submit">Update Supplier</button>
        </form>

        <!-- Link back to the supplier list page -->
        <a href="suppliers.php" class="btn back-btn">Back to Supplier List</a>
    </div>
</body>

</html>