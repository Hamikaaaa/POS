<?php
session_start();
require __DIR__ . '/../db.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if order data exists in POST
if (!isset($_POST['cart_data'])) {
    echo "Please provide a valid Order ID.";
    exit();
}

// Decode the JSON cart data
$orderData = json_decode($_POST['cart_data'], true);

if (!$orderData) {
    echo "Invalid order data.";
    exit();
}

// Insert order into database
$userId = $_SESSION['user_id'];
$deliveryAddress = json_encode($orderData['address']);
$paymentMethod = $orderData['payment']['method'];
$paymentReference = $orderData['payment']['reference'];
$subtotal = $orderData['amounts']['subtotal'];
$deliveryFee = $orderData['amounts']['deliveryFee'];
$totalAmount = $orderData['amounts']['total'];

// Handle file upload for GCash payments
if ($paymentMethod === 'gcash' && isset($_FILES['payment_proof'])) {
  $uploadDir = 'uploads/payment_proofs/';
  
  // Create directory if it doesn't exist
  if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
  }
  
  // Check if file was uploaded successfully
  if ($_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
      // Get file extension
      $fileExtension = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
      
      // Validate file type
      $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
      if (in_array($fileExtension, $allowedTypes)) {
          // Generate unique filename
          $fileName = 'payment_' . time() . '_' . uniqid() . '.' . $fileExtension;
          $targetPath = $uploadDir . $fileName;
          
          // Move uploaded file
          if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetPath)) {
              $paymentProof = $fileName;
          } else {
              echo "Error uploading file.";
              exit();
          }
      } else {
          echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
          exit();
      }
  } else {
      echo "File upload failed with error code: " . $_FILES['payment_proof']['error'];
      exit();
  }
}

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, delivery_address, payment_method, payment_reference, payment_proof, subtotal, delivery_fee, total_amount, order_type, created_at) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$orderType = 'delivery'; // or get it from form data if needed

$subtotalValue = isset($subtotal) ? (float)$subtotal : 0.0;
$deliveryFeeValue = isset($deliveryFee) ? (float)$deliveryFee : 0.0;
$totalAmountValue = isset($totalAmount) ? (float)$totalAmount : 0.0;

// Fix: Changed bind_param types to match the number of parameters
$stmt->bind_param("issssddds", 
    $userId,                // i - integer
    $deliveryAddress,       // s - string
    $paymentMethod,        // s - string
    $paymentReference,     // s - string
    $paymentProof,         // s - string (nullable if needed)
    $subtotalValue,        // d - double
    $deliveryFeeValue,     // d - double
    $totalAmountValue,     // d - double
    $orderType             // s - string
);


$stmt->execute();
$orderId = $conn->insert_id;

// Insert order items
foreach ($orderData['cart'] as $item) {
    $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Get user details
$userQuery = $conn->query("SELECT username FROM users WHERE id = $userId");
$user = $userQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt - SukariKo!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .print-only {
                display: block;
            }
        }
        .custom-back-link {
        display: inline-block;
        margin-left: 1rem;
        padding: 0.5rem 1rem;
        background-color: #ec6d10; /* soft orange background */
        color: #fff7ed;
        text-decoration: none;
        font-weight: 500;
        border-radius: 0.375rem; /* rounded-md */
        transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
    }

    .custom-back-link:hover {
        color: #ea580c;
        background-color: #ffedd5; /* darker orange on hover */
    }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
            <!-- Receipt Header -->
            <div class="text-center mb-6">
            <h1 style="color: #ec6d10; font-size: 3rem; font-weight: bold;">
  SukariKo!
</h1>

                <h1 class="text-2xl font-bold">Receipt</h1>
                <p class="text-gray-600">Order #<?php echo $orderId; ?></p>
                <p class="text-gray-600"><?php echo date('F d, Y h:i A'); ?></p>
            </div>

            <!-- Customer Details -->
            <div class="mb-6">
                <h2 class="font-bold mb-2">Customer Details</h2>
                <p>Name: <?php echo htmlspecialchars($orderData['address']['fullName']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($orderData['address']['phoneNumber']); ?></p>
                <p>Address: <?php 
                    echo htmlspecialchars($orderData['address']['streetAddress']) . ", " .
                         htmlspecialchars($orderData['address']['barangay']) . ", " .
                         htmlspecialchars($orderData['address']['city']);
                ?></p>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h2 class="font-bold mb-2">Order Items</h2>
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Item</th>
                            <th class="text-center py-2">Qty</th>
                            <th class="text-right py-2">Price</th>
                            <th class="text-right py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderData['cart'] as $item): ?>
                        <tr class="border-b">
                            <td class="py-2"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="text-center py-2"><?php echo $item['quantity']; ?></td>
                            <td class="text-right py-2">₱<?php echo number_format($item['price'], 2); ?></td>
                            <td class="text-right py-2">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Order Summary -->
            <div class="border-t pt-4">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>₱<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Delivery Fee:</span>
                    <span>₱<?php echo number_format($deliveryFee, 2); ?></span>
                </div>
                <div class="flex justify-between font-bold text-lg">
                    <span>Total:</span>
                    <span>₱<?php echo number_format($totalAmount, 2); ?></span>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="mt-6">
                <h2 class="font-bold mb-2">Payment Details</h2>
                <p>Method: <?php echo ucfirst($paymentMethod); ?></p>
                <?php if ($paymentMethod === 'gcash'): ?>
                    <p>Reference Number: <?php echo htmlspecialchars($paymentReference); ?></p>
                    <?php if ($paymentProof): ?>
                        <p>Payment Proof: <a href="<?php echo $uploadDir . $paymentProof; ?>" target="_blank" class="text-blue-500 hover:text-blue-700">View Screenshot</a></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Print Button -->
            <div class="mt-6 text-center no-print">
            <a href="cusView.php" class="custom-back-link">
    Back To Menu
</a>
            </div>
        </div>
    </div>
</body>
</html>