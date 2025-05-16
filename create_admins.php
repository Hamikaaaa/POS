<?php
require 'db.php'; // Ensure this connects properly to your database

// Function to create a user with a hashed password
function createAdminUser($pdo, $username, $password, $role) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $hashedPassword, $role]);
}

// Insert Super Admin and Admin
$success1 = createAdminUser($pdo, 'superadmin', 'admin123', 'super_admin');
$success2 = createAdminUser($pdo, 'admin', 'admin123', 'admin');

if ($success1 && $success2) {
    echo "Super Admin and Admin created successfully!";
} else {
    echo "Error creating users. They might already exist.";
}
?>
