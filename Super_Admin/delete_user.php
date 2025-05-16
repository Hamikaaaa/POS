<?php
session_start();
require __DIR__ . '/../db.php';

// Check if user is logged in and is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../index.php'); // Redirect to index if not authorized
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: users.php'); // Redirect if no ID is provided
    exit();
}

$userId = $_GET['id'];

// Validate the user ID (ensure it's a valid integer)
if (!filter_var($userId, FILTER_VALIDATE_INT)) {
    header('Location: users.php'); // Redirect if invalid user ID
    exit();
}

try {
    // Prepare the DELETE query to remove the user from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the users page with success message
        header('Location: users.php?message=User deleted successfully');
        exit();
    } else {
        // Redirect back to the users page with error message
        header('Location: users.php?error=Failed to delete user');
        exit();
    }
} catch (PDOException $e) {
    // Handle any errors
    die("Database error: " . $e->getMessage());
}