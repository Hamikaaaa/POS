<?php
session_start();

require __DIR__ . '/../db.php';

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;

// Fetch order details from DB
$order = [];
$order_details = [];
if ($order_id) {
    // Get order info
    $order_query = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $order_query->bind_param('i', $order_id);
    $order_query->execute();
    $order = $order_query->get_result()->fetch_assoc();

    // Get order details
    $details_query = $conn->prepare("
        SELECT od.*, p.name AS product_name, p.price AS product_price 
        FROM order_details od 
        JOIN products p ON od.product_id = p.id 
        WHERE od.order_id = ?
    ");
    $details_query->bind_param('i', $order_id);
    $details_query->execute();
    $order_details = $details_query->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Initialize values
$discount_type = $order['discount_type'] ?? 'none';
$is_discounted = in_array($discount_type, ['senior_citizen', 'pwd']);
$discount_rate = $is_discounted ? 0.20 : 0;
$vat_rate = 0.12;

// Calculate totals
$raw_total = 0;
foreach ($order_details as $item) {
    $raw_total += $item['product_price'] * $item['quantity'];
}

$discount_amount = $raw_total * $discount_rate;
$vatable_sales = $raw_total - $discount_amount;
$vat_amount = $vatable_sales * $vat_rate;
$final_total = $vatable_sales + $vat_amount;

// Use payment/change from GET or fallback to calculated
$payment = isset($_GET['payment']) ? floatval($_GET['payment']) : $final_total;
$change = isset($_GET['change']) ? floatval($_GET['change']) : max(0, $payment - $final_total);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            background-color: white;
            width: 320px;
            padding: 15px;
            margin: 0 auto;
            border: 1px solid #ddd;
            font-size: 12px;
            line-height: 1.4;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .receipt-details {
            margin-bottom: 10px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }
        .receipt-details th, .receipt-details td {
            padding: 4px 0;
            text-align: left;
            font-size: 12px;
        }
        .receipt-footer {
            margin-top: 20px;
        }
        .receipt-footer p {
            margin: 4px 0;
        }
        .line {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }
    </style>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</head>
<body>
<a href="cashier.php" style="display: inline-block; padding: 10px 20px; background-color: #ec6d10; color: white; text-decoration: none; border-radius: 8px; font-family: Arial, sans-serif; font-size: 16px;">Back</a>

<div class="receipt-container">
    <div class="receipt-header">
        <h1>Sukariko Receipt</h1>
        <p>Order ID: <?= htmlspecialchars($order_id); ?></p>
        <p>Date: <?= date('Y-m-d H:i'); ?></p>
    </div>

    <div class="receipt-details">
        <table width="100%" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th style="width: 25%;">Product</th>
                    <th style="text-align: right; width: 20%;">Price</th>
                    <th style="text-align: center; width: 25%;">Qty</th>
                    <th style="text-align: right; width: 30%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_details as $item): 
                    $subtotal = $item['product_price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']); ?></td>
                    <td style="text-align: right;">P <?= number_format($item['product_price'], 2); ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($item['quantity']); ?></td>
                    <td style="text-align: right;">P <?= number_format($subtotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="line"></div>

    <div class="receipt-footer">
    <p><strong>Total: </strong>P <?= number_format($raw_total, 2); ?></p>
    
    <!-- Enhanced discount status display -->
    <p><strong>Status: </strong>
    <?php
        if (!empty($order['discount_type'])) {
            switch($order['discount_type']) {
                case 'senior_citizen':
                    echo 'Senior Citizen';
                    break;
                case 'pwd':
                    echo 'PWD';
                    break;
                default:
                    echo 'Regular';
            }
        } else {
            echo 'Regular';
        }
    ?>
    </p>

    <?php if ($discount_amount > 0): ?>
        <p><strong>Discount Amount (20%): </strong>-P <?= number_format($discount_amount, 2); ?></p>
    <?php endif; ?>

    <p><strong>Vatable Amount: </strong>P <?= number_format($vatable_sales, 2); ?></p>
    <p><strong>VAT (12%): </strong>P <?= number_format($vat_amount, 2); ?></p>
    <p><strong>Payment: </strong>P <?= number_format($payment, 2); ?></p>
    <p><strong>Change: </strong>P <?= number_format($change, 2); ?></p>

        <div class="line"></div>

        <div style="text-align: center; margin-top: 10px;">
            <p><strong>Thank you for shopping with us!</strong></p>
            <p>info@sukariko.com</p>
            <p>123-456-7890</p>
        </div>
    </div>
</div>


</body>
</html>