<?php
require __DIR__ . '/../db.php'; // Ensure correct path to db.php

if (isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];

    // Fetch product details including stock availability
    $stmt = $conn->prepare("SELECT name, price, stock FROM products WHERE barcode = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        // Check if the product is out of stock
        if ($product['stock'] <= 0) {
            echo json_encode(["error" => "This product is out of stock."]);
        } else {
            echo json_encode($product);
        }
    } else {
        echo json_encode(["error" => "Product not found"]);
    }

    $stmt->close();
    $conn->close();
}
?>