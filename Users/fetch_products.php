<?php
include 'db.php'; // Your database connection file

header('Content-Type: application/json');

$query = "SELECT id, name, stock, price FROM products";
$result = mysqli_query($conn, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>
