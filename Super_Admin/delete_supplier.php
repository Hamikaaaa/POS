<?php
session_start();
require __DIR__ . '/../db.php'; // Ensure this contains the correct database connection

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}

// Check if a valid supplier ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $supplier_id = $_GET['id'];

    try {
        // Delete the supplier from the database
        $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = :id");
        $stmt->bindParam(':id', $supplier_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirect to the suppliers list page after successful deletion
            header('Location: suppliers.php?message=Supplier deleted successfully.');
            exit();
        } else {
            echo "<p>Failed to delete supplier. Please try again.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Database error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Invalid supplier ID.</p>";
    exit();
}
