<?php
// update_cart.php

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if cart data exists
    if (isset($data['cart'])) {
        $cart = $data['cart'];

        // Assuming you already have a database connection setup
        // Replace these with your actual database connection
        $host = 'localhost';
        $db = 'your_database';
        $user = 'your_username';
        $pass = 'your_password';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Loop through the cart and update the database
            foreach ($cart as $item) {
                $productId = $item['id'];
                $quantity = $item['quantity'];

                // Assuming a user_id is available, you can get the logged-in user ID
                // For demonstration purposes, we'll use a static user ID of 1
                $userId = 1;

                // Check if the item already exists in the user's cart
                $stmt = $pdo->prepare("SELECT * FROM cart WHERE product_id = :product_id AND user_id = :user_id");
                $stmt->execute([':product_id' => $productId, ':user_id' => $userId]);
                $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingItem) {
                    // Update the existing cart item
                    $stmt = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE product_id = :product_id AND user_id = :user_id");
                    $stmt->execute([':quantity' => $quantity, ':product_id' => $productId, ':user_id' => $userId]);
                } else {
                    // Insert new cart item
                    $stmt = $pdo->prepare("INSERT INTO cart (product_id, user_id, quantity) VALUES (:product_id, :user_id, :quantity)");
                    $stmt->execute([':product_id' => $productId, ':user_id' => $userId, ':quantity' => $quantity]);
                }
            }

            // Return a response to indicate success
            echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully']);

        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No cart data found']);
    }
}
?>
