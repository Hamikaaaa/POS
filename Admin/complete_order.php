<?php
session_start();
require __DIR__ . '/../db.php';

header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

function debug_log($message, $data = null) {
    $log_message = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $log_message .= " - Data: " . json_encode($data);
    }
    error_log($log_message);
}

debug_log("Order process started");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

if (!isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(['success' => false, 'error' => 'Cart data is missing or empty']);
    exit;
}

if (!isset($data['payment'])) {
    echo json_encode(['success' => false, 'error' => 'Payment data is missing']);
    exit;
}

// Handle optional fields
$discount_type = $data['discount_type'] ?? 'none';
$order_type = $data['order_type'] ?? 'dine-in';
$payment_method = $data['payment_method'] ?? 'cash';
$delivery_charge = floatval($data['delivery_charge'] ?? 0);
$payment_reference = $data['payment_reference'] ?? null;

$discount = 0;
if (in_array($discount_type, ['senior_citizen', 'pwd'])) {
    $discount = 0.20;
}

$cart = $data['cart'];
$payment = floatval($data['payment']);
$user_id = $_SESSION['user_id'] ?? 0;

try {
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    if (!$conn->begin_transaction()) {
        throw new Exception("Failed to start transaction: " . $conn->error);
    }

    $order_total = 0;
    $total_quantity = 0;
    $product_id = null;  // Initialize product_id

    // Get the first product's ID before inserting into orders
    if (!empty($cart)) {
        $first_item = reset($cart);
        $product_stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
        $product_stmt->bind_param("s", $first_item['name']);
        $product_stmt->execute();
        $result = $product_stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
        } else {
            throw new Exception("Product not found: " . $first_item['name']);
        }
    }

    foreach ($cart as $item) {
        if (!isset($item['subtotal']) || !isset($item['qty'])) {
            throw new Exception("Invalid cart item format");
        }
        $order_total += $item['subtotal'];
        $total_quantity += $item['qty'];
    }

    $discount_amount = $order_total * $discount;
    $total_after_discount = $order_total - $discount_amount;
    $change_due = $payment - $total_after_discount;

    // Insert into orders table with payment_reference and product_id
    $order_stmt = $conn->prepare("
        INSERT INTO orders 
        (user_id, total, created_at, quantity, total_amount, order_type, payment_method, delivery_charge, change_due, discount_type, payment_reference, product_id) 
        VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $order_stmt->bind_param(
        "idiidsssssd",
        $user_id,
        $total_after_discount,
        $total_quantity,
        $total_after_discount,
        $order_type,
        $payment_method,
        $delivery_charge,
        $change_due,
        $discount_type,
        $payment_reference,
        $product_id
    );

    if (!$order_stmt->execute()) {
        throw new Exception("Failed to insert order: " . $order_stmt->error);
    }

    $order_id = $conn->insert_id;

    foreach ($cart as $item) {
        if (!isset($item['name'], $item['price'], $item['qty'])) {
            throw new Exception("Cart item missing required fields");
        }

        $product_stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
        $product_stmt->bind_param("s", $item['name']);
        $product_stmt->execute();
        $result = $product_stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];

            // Insert into order_details table
            $detail_stmt = $conn->prepare("
                INSERT INTO order_details (order_id, product_id, quantity, price, discount) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $discount_each = $item['discount'] ?? 0;
            $detail_stmt->bind_param("iiidi", $order_id, $product_id, $item['qty'], $item['price'], $discount_each);
            if (!$detail_stmt->execute()) {
                throw new Exception("Failed to insert order details: " . $detail_stmt->error);
            }

            // Insert into sales table
            $sales_stmt = $conn->prepare("
                INSERT INTO sales (product_id, amount, quantity, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $sales_stmt->bind_param("idi", $product_id, $item['subtotal'], $item['qty']);
            if (!$sales_stmt->execute()) {
                throw new Exception("Failed to insert sales: " . $sales_stmt->error);
            }

        } else {
            throw new Exception("Product not found: " . $item['name']);
        }
    }

    // Insert payment record into payments table
    $payment_stmt = $conn->prepare("
        INSERT INTO payments (order_id, payment_method, amount, created_at, status) 
        VALUES (?, ?, ?, NOW(), 'completed')
    ");
    $payment_stmt->bind_param("isd", $order_id, $payment_method, $payment);
    if (!$payment_stmt->execute()) {
        throw new Exception("Failed to insert payment: " . $payment_stmt->error);
    }

    // Commit transaction
    if (!$conn->commit()) {
        throw new Exception("Failed to commit transaction: " . $conn->error);
    }

    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    $conn->rollback();
    error_log("Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>

