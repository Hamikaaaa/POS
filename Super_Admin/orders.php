<?php
session_start();
require __DIR__ . '/../db.php'; // Include database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (empty($_POST['customer_id']) || empty($_POST['total_amount'])) {
        die("Error: Missing required fields.");
    }

    $customer_id = intval($_POST['customer_id']); // Convert to integer
    $total_amount = floatval($_POST['total_amount']); // Convert to float
    $status = "Pending"; // Default order status

    try {
        // Insert order into the database
        $sql = "INSERT INTO orders (customer_id, total_amount, status, created_at) 
                VALUES (:customer_id, :total_amount, :status, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();

        // Get the inserted order ID
        $order_id = $pdo->lastInsertId();

        // Insert into payments table
        $payment_sql = "INSERT INTO payments (order_id, payment_method, amount, status, created_at)
                        VALUES (:order_id, 'cash', :amount, 'completed', NOW())";

        $payment_stmt = $pdo->prepare($payment_sql);
        $payment_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $payment_stmt->bindParam(':amount', $total_amount, PDO::PARAM_STR);
        $payment_stmt->execute();

        // Redirect to orders page
        header("Location: orders.php");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F4F6F9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: rgb(0, 0, 0);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            padding: 10px 15px;
            background: #11998e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            width: 100%;
        }

        .btn:hover {
            background: #38ef7d;
        }

        .back-btn {
            margin-top: 15px;
            display: block;
            text-decoration: none;
            color: white;
            background: #004d40;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #026960;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Add Order</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="customer_id">Customer ID</label>
                <input type="number" id="customer_id" name="customer_id" required>
            </div>

            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="number" id="total_amount" name="total_amount" step="0.01" required>
            </div>

            <button type="submit" class="btn">Submit Order</button>
        </form>

        <a href="super_admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

</body>

</html>