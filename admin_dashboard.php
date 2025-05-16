<?php
session_start();
require 'db.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h1>Welcome, Admin <?= $user['username'] ?>!</h1>
<p>Your role: <?= $user['role'] ?></p>

<!-- Links for admin to manage products -->
<a href="manage_products.php">Manage Products</a>
<a href="view_orders.php">View Orders</a>

<a href="logout.php">Logout</a>
