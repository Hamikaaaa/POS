<?php
session_start();
require_once '../db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Log incoming data
error_log('Received order data: ' . file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

try {
    // Get and decode the JSON data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    // Validate required data
    if (empty($data['cart']) || empty($data['address'])) {
        throw new Exception('Missing required data: cart or address');
    }

    // Start transaction
    $conn->begin_transaction();

    // Save delivery address first
    $addressSql = "INSERT INTO delivery_addresses 
        (user_id, full_name, phone_number, city, barangay, street_address) 
        VALUES (?, ?, ?, ?, ?, ?)";
    
    $addressStmt = $conn->prepare($addressSql);
    if (!$addressStmt) {
        throw new Exception('Address prepare failed: ' . $conn->error);
    }

    $addressStmt->bind_param("isssss",
        $_SESSION['user_id'],
        $data['address']['fullName'],
        $data['address']['phoneNumber'],
        $data['address']['city'],
        $data['address']['barangay'],
        $data['address']['streetAddress']
    );

    if (!$addressStmt->execute()) {
        throw new Exception('Failed to save address: ' . $addressStmt->error);
    }

    $addressId = $conn->insert_id;

    // Create the order
    $orderSql = "INSERT INTO orders 
        (user_id, delivery_address_id, total_amount, subtotal, delivery_charge, 
        order_type, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, 'delivery', ?, 'pending')";

    $orderStmt = $conn->prepare($orderSql);
    if (!$orderStmt) {
        throw new Exception('Order prepare failed: ' . $conn->error);
    }

    $orderStmt->bind_param("iiddds",
        $_SESSION['user_id'],
        $addressId,
        $data['totalAmount'],
        $data['subtotal'],
        $data['deliveryCharge'],
        $data['paymentMethod']
    );

    if (!$orderStmt->execute()) {
        throw new Exception('Failed to create order: ' . $orderStmt->error);
    }

    $orderId = $conn->insert_id;

    // Save order details
    $detailSql = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                  VALUES (?, ?, ?, ?)";
    
    $detailStmt = $conn->prepare($detailSql);
    if (!$detailStmt) {
        throw new Exception('Detail prepare failed: ' . $conn->error);
    }

    foreach ($data['cart'] as $item) {
        // Verify stock availability first
        $stockSql = "SELECT stock FROM products WHERE id = ?";
        $stockStmt = $conn->prepare($stockSql);
        $stockStmt->bind_param("i", $item['id']);
        $stockStmt->execute();
        $result = $stockStmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
            throw new Exception("Product not found: ID " . $item['id']);
        }

        if ($product['stock'] < $item['quantity']) {
            throw new Exception("Insufficient stock for product ID " . $item['id']);
        }

        // Insert order detail
        $detailStmt->bind_param("iiid",
            $orderId,
            $item['id'],
            $item['quantity'],
            $item['price']
        );

        if (!$detailStmt->execute()) {
            throw new Exception('Failed to save order detail: ' . $detailStmt->error);
        }

        // Update product stock
        $updateSql = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $item['quantity'], $item['id']);
        
        if (!$updateStmt->execute()) {
            throw new Exception('Failed to update product stock: ' . $updateStmt->error);
        }
    }

    // Commit the transaction
    $conn->commit();
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'orderId' => $orderId,
        'message' => 'Order processed successfully'
    ]);

} catch (Exception $e) {
    // Log the error
    error_log('Order processing error: ' . $e->getMessage());
    
    // Rollback transaction if active
    if (isset($conn) && !$conn->connect_error) {
        $conn->rollback();
    }

    // Send error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Close any open statements
if (isset($addressStmt)) $addressStmt->close();
if (isset($orderStmt)) $orderStmt->close();
if (isset($detailStmt)) $detailStmt->close();
if (isset($stockStmt)) $stockStmt->close();
if (isset($updateStmt)) $updateStmt->close();
$conn->close();
?>