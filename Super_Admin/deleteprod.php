<?php
require __DIR__ . '/../db.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Ensure it's an integer for safety

    // Use a prepared statement to securely delete the product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute() && $stmt->affected_rows === 1) {
        echo "<script>alert('Product deleted successfully!'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Error deleting product or product not found.'); window.location.href='products.php';</script>";
    }

    $stmt->close();
}
?>