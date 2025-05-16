<?php
require __DIR__ . '/../db.php'; // Adjust path if needed

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Response template
$response = ['success' => false];

if (!isset($data['cart']) || !is_array($data['cart'])) {
    $response['error'] = 'Invalid cart data.';
    echo json_encode($response);
    exit;
}

try {
    foreach ($data['cart'] as $item) {
        $name = $item['name'];
        $qty = (int)$item['qty'];

        // Get the product's current stock and validate
        $stmt = $conn->prepare("SELECT stock FROM products WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
            $response['error'] = "Product not found: $name";
            echo json_encode($response);
            exit;
        }

        if ($product['stock'] < $qty) {
            $response['error'] = "Insufficient stock for: $name";
            echo json_encode($response);
            exit;
        }

        // Update stock
        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE name = ?");
        $stmt->bind_param("is", $qty, $name);
        $stmt->execute();
    }

    $response['success'] = true;
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);