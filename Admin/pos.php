<?php
session_start();
require __DIR__ . '/../db.php'; 

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// Add product to cart using barcode
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["barcode"])) {
    $barcode = $_POST["barcode"];
    $sql = "SELECT * FROM products WHERE barcode = '$barcode'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Store stock properly
        $currentStock = $product["stock"];

        if (!isset($_SESSION["cart"][$barcode])) {
            $_SESSION["cart"][$barcode] = [
                "name" => $product["name"],
                "price" => $product["price"],
                "quantity" => 1,
                "stock" => $currentStock // Store stock value
            ];
        } else {
            // Check if adding another item exceeds stock
            if ($_SESSION["cart"][$barcode]["quantity"] < $currentStock) {
                $_SESSION["cart"][$barcode]["quantity"]++;
            } else {
                echo "<p style='color:red;'>Not enough stock available!</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>Product not found!</p>";
    }
}

// Checkout and update stock
if (isset($_POST["checkout"])) {
    foreach ($_SESSION["cart"] as $barcode => $item) {
        $currentStock = $item["stock"]; // Get the stock from session
        $newStock = $currentStock - $item["quantity"];

        if ($newStock < 0) {
            echo "<p style='color:red;'>Error: Not enough stock for {$item["name"]}</p>";
            continue;
        }

        $updateStockSql = "UPDATE products SET stock = $newStock WHERE barcode = '$barcode'";
        $conn->query($updateStockSql);
    }
    
    // Clear cart after checkout
    $_SESSION["cart"] = [];
    echo "<p style='color:green;'>Checkout successful! Stock updated.</p>";
}
?>

<form method="POST">
    <input type="text" name="barcode" placeholder="Scan or Type Barcode" autofocus>
    <button type="submit">Add to Cart</button>
</form>

<h2>Cart Summary</h2>
<table border="1">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>
    <?php 
    $total = 0;
    foreach ($_SESSION["cart"] as $barcode => $item):
        $subtotal = $item["price"] * $item["quantity"];
        $total += $subtotal;
    ?>
    <tr>
        <td><?php echo $item["name"]; ?></td>
        <td><?php echo $item["price"]; ?></td>
        <td><?php echo $item["quantity"]; ?></td>
        <td><?php echo $subtotal; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<h3>Total: <?php echo $total; ?></h3>

<form method="POST">
    <button type="submit" name="checkout">Checkout</button>
</form>

<a href="clear_cart.php">Clear Cart</a>
<a href="cashier.php">Back</a>