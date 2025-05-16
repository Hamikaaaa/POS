<?php
// process_order.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/inventory_functions.php'; // Ensure this exists (from earlier)

session_start();
header('Content-Type: application/json');

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

// Get order data (assumes JSON input or form data)
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$order_items = $input['items'] ?? []; // Format: [ [product_id, quantity], ... ]
$customer_id = $input['customer_id'] ?? null;

if (empty($order_items)) {
    http_response_code(400);
    die(json_encode(['error' => 'No items in order']));
}

try {
    $pdo->beginTransaction();

    // 1. Create order record
    $stmt = $pdo->prepare("
        INSERT INTO orders (customer_id, total, status, created_at) 
        VALUES (?, 0, 'completed', NOW())
    ");
    $stmt->execute([$customer_id]);
    $order_id = $pdo->lastInsertId();

    // 2. Process each item
    $total = 0;
    foreach ($order_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        // Fetch product price and recipe
        $stmt = $pdo->prepare("
            SELECT p.price, p.recipe_id 
            FROM products p 
            WHERE p.id = ?
        ");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            throw new Exception("Product $product_id not found");
        }

        // Add to order_items table
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$order_id, $product_id, $quantity, $product['price']]);
        $total += $product['price'] * $quantity;

        // Deduct inventory if product has a recipe
        if (!empty($product['recipe_id'])) {
            $success = deductRecipeIngredients($product['recipe_id'], $quantity);
            if (!$success) {
                throw new Exception("Failed to deduct inventory for product $product_id");
            }
        }
    }

    // 3. Update order total
    $stmt = $pdo->prepare("UPDATE orders SET total = ? WHERE id = ?");
    $stmt->execute([$total, $order_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}