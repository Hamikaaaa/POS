<?php
session_start();
require_once '../db.php'; // Include your DB connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Receive and decode the cart data from the request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid cart data']);
    exit;
}

$cart = $data['cart'];

// Start transaction to update stocks
$conn->begin_transaction();

try {
    foreach ($cart as $item) {
        $productId = $item['id'];
        $quantity = $item['quantity'];

        // Check if enough stock is available
        $stockSql = "SELECT stock FROM products WHERE id = ?";
        $stmt = $conn->prepare($stockSql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product && $product['stock'] >= $quantity) {
            // Deduct stock from the product
            $updateSql = "UPDATE products SET stock = stock - ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('ii', $quantity, $productId);
            $updateStmt->execute();
        } else {
            // If insufficient stock, rollback and return error
            throw new Exception("Insufficient stock for product ID " . $productId);
        }
    }

    // Commit the transaction if all stock updates are successful
    $conn->commit();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // Close any open statements
    if (isset($stmt)) $stmt->close();
    if (isset($updateStmt)) $updateStmt->close();
    $conn->close();
}
?>
