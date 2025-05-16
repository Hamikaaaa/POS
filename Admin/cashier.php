<?php
session_start();
require __DIR__ . '/../db.php';

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /POS/index.php");
    exit();
}

// Fetch user information
$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT username FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();

// Fetch categories from the database
try {
    $category_query = $pdo->query("SELECT * FROM categories");
    $categories = $category_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Fetch all products initially
$product_query = $conn->query("SELECT * FROM products");
$products = $product_query->fetch_all(MYSQLI_ASSOC);

foreach ($products as &$product) {
    $product['barcode'] = $product['barcode'] ?? '';  // Ensure barcode field is present
}

// Initialize products array
$products = [];

// Fetch all products if no category is selected or if 'all' is explicitly chosen
if (!isset($_GET['category']) || $_GET['category'] == 'all') {
    $product_query = $conn->query("SELECT * FROM products");
    if ($product_query) {
        $products = $product_query->fetch_all(MYSQLI_ASSOC);
    }
} else {
    // Fetch products for a specific category
    $category_code = $_GET['category'];
    $product_query = $conn->prepare("SELECT * FROM products WHERE category_code = ?");
    $product_query->bind_param('s', $category_code);
    $product_query->execute();
    $result = $product_query->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle search term and category filter
$searchTerm = '';
$categoryFilter = '';

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}
if (isset($_GET['category'])) {
    $categoryFilter = $_GET['category'];
}

// Build the query with search and category filter
$query = "SELECT * FROM products WHERE 1=1";
if ($searchTerm) {
    $query .= " AND name LIKE '%$searchTerm%'";
}
if ($categoryFilter) {
    $query .= " AND category_code = '$categoryFilter'";
}

// Execute the query to get products from the database
$product_query = $conn->query($query);
$products = $product_query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #F3F4F6;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 500px;
        }
        #checkout-modal {
    display: none; /* Initially hidden */
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
    z-index: 100;
    justify-content: center;
    align-items: center;
}
h2 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 16px;
}
/* Order Summary List */
#order-list {
    list-style-type: none;
    padding-left: 0;
    margin-bottom: 16px;
}

#vat-amount, #total-amount, #change-display {
    font-size: 14px;
    color: #333;
}

#discount-amount {
    color: #ec6d10;
}

/* Discount Applied Text */
#discount-20-section {
    font-size: 14px;
    color: #28a745;
}

/* Select and Input Styles */
select, input[type="number"] {
    width: 100%;
    padding: 8px;
    border: 2px solid #ec6d10;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 14px;
}

select:focus, input[type="number"]:focus {
    border-color: #ec6d10;
    outline: none;
    box-shadow: 0 0 5px rgba(236, 109, 16, 0.6);
}

/* Buttons */
button {
    padding: 10px 16px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Cancel Button */
button.bg-gray-500 {
    background-color: #6c757d;
    color: white;
}

button.bg-gray-500:hover {
    background-color: #5a6268;
}

/* Complete Order Button */
button.bg-green-500 {
    background-color: #ec6d10;
    color: white;
}

button.bg-green-500:hover {
    background-color:rgba(232, 124, 47, 0.63);
}

/* Payment Reference Container */
#gcash-ref-container {
    margin-top: 16px;
}

/* Modal Footer */
.mt-4 {
    margin-top: 16px;
}

/* Show the modal */
.hidden {
    display: none !important;
}

/* Adjust the modal position for better visibility */
#checkout-modal .modal-content {
    max-width: 500px;
    padding: 20px;
    border-radius: 8px;
}
        /* Improved Error Message Notification */
#notification {
    
    position: fixed;
  top: 20px;
  left: 40%;
  
  z-index: 50;
  padding: 12px 16px;
  border-radius: 4px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  font-weight: 500;
  max-width: 320px;
  transition: all 0.3s ease-in-out;
  opacity: 0;
  animation: slideIn 0.3s forwards;


}

/* Error Style */
#notification.error-message {
  background-color:rgb(248, 240, 215);
  color: #ec6d10;
}

/* Success Style - keeping for completeness */
#notification.success-message {
  background-color: rgb(248, 240, 215);
  color:  #ec6d10;
}

/* Warning Style - keeping for completeness */
#notification.warning-message {
  background-color: rgb(248, 240, 215);
  color: #ec6d10;
}

@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeOut {
  to {
    opacity: 0;
    transform: translateY(-10px);
  }
}

#notification.fade-out {
  animation: fadeOut 0.3s forwards;
}
    </style>
    <script>
        let cart = [];

        // Function to show notifications with color coding based on type
        // Function to show notifications with CSS styling based on type
function showNotification(message, type = "error") {
    const notif = document.getElementById("notification");
    notif.textContent = message;
    notif.classList.remove("hidden");
    
    // Remove all previous message type classes
    notif.classList.remove("error-message", "success-message", "warning-message", "bg-red-500", "bg-green-500", "bg-yellow-500");
    
    // Add the appropriate class based on message type
    if (type === "success") {
        notif.classList.add("success-message");
    } else if (type === "warning") {
        notif.classList.add("warning-message");
    } else {
        notif.classList.add("error-message");
    }
    
    // Add animation class
    notif.classList.remove("fade-out");
    
    // Clear any existing timeout
    if (notif.timeoutId) {
        clearTimeout(notif.timeoutId);
    }
    
    // Set timeout to hide the notification
    notif.timeoutId = setTimeout(() => {
        notif.classList.add("fade-out");
        setTimeout(() => {
            notif.classList.add("hidden");
        }, 300); // Match this to the fadeOut animation duration
    }, 3000);
}
        // Function to add a product to the cart
        // Modify the addToCart function
function addToCart(productName, productPrice, stock, productId) { // Add productId parameter
    let existingProduct = cart.find(item => item.name === productName);
    if (existingProduct) {
        if (existingProduct.qty >= existingProduct.stock) {
            showNotification(`Cannot add more! Can cater ${existingProduct.stock} orders left.`);
            return;
        }
        existingProduct.qty++;
        existingProduct.subtotal = existingProduct.price * existingProduct.qty;
    } else {
        if (stock <= 0) {
            showNotification("This product is not available.");
            return;
        }
        cart.push({
            product_id: productId, // Add this line
            name: productName,
            price: productPrice,
            qty: 1,
            subtotal: productPrice,
            stock: stock
        });
    }
    renderCart();
}

        // Function to remove an item from the cart
        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        // Function to clear the entire cart
        function clearCart() {
            cart = [];
            renderCart();
        }

        // Function to display the cart contents
        function renderCart() {
            const cartTable = document.getElementById("cart-table");
            const cartRows = cart.map((item, index) => `
                <tr class="text-center border-b border-gray-300">
                    <td class="p-2">${item.name}</td>
                    <td class="p-2">P${item.price.toFixed(2)}</td>
                    <td class="p-2 flex items-center justify-center space-x-2">
                        <button class="bg-gray-300 text-black px-2 py-1 rounded ${item.qty <= 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                            onclick="decreaseQty(${index})"
                            ${item.qty <= 1 ? 'disabled' : ''}>
                            -
                        </button>
                        <span>${item.qty}</span>
                        <button class="bg-gray-300 text-black px-2 py-1 rounded ${item.qty >= item.stock ? 'opacity-50 cursor-not-allowed' : ''}" 
                            onclick="increaseQty(${index})"
                            ${item.qty >= item.stock ? 'disabled' : ''}>
                            +
                        </button>
                    </td>
                    <td class="p-2">P${item.subtotal.toFixed(2)}</td>
                    <td class="p-2">
                        <button class="bg-red-500 text-white px-2 py-1 rounded transition hover:bg-red-700" onclick="removeFromCart(${index})">
                            Remove
                        </button>
                    </td>
                </tr>
            `).join("");
            cartTable.innerHTML = `
                <tr class="bg-white text-[#EC6D10] text-center">
                    <th class="p-2">Name</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Qty</th>
                    <th class="p-2">Subtotal</th>
                    <th class="p-2">Action</th>
                </tr>
                ${cartRows || '<tr><td colspan="5" class="text-center p-2">Your cart is empty.</td></tr>'}
            `;
            const grandTotal = cart.reduce((acc, item) => acc + item.subtotal, 0);
            document.getElementById("grand-total").innerText = `Grand Total: P${grandTotal.toFixed(2)}`;
        }

        // Function to increase quantity
        function increaseQty(index) {
            if (cart[index].qty < cart[index].stock) {
                cart[index].qty++;
                cart[index].subtotal = cart[index].qty * cart[index].price;
            } else {
                showNotification("Not enough stock available!");
            }
            renderCart();
        }

        // Function to decrease quantity
        function decreaseQty(index) {
            if (cart[index].qty > 1) {
                cart[index].qty--;
                cart[index].subtotal = cart[index].qty * cart[index].price;
            } else {
                removeFromCart(index);
            }
            renderCart();
        }

 // Function to calculate VAT and update the order summary
function calculateVat() {
    const total = cart.reduce((acc, item) => acc + item.subtotal, 0);
    // Correct VAT calculation - extract VAT from total price
    const vat = total * (0.12 / 1.12); // Extract 12% VAT from prices that include VAT
    document.getElementById("vat-amount").innerText = `VAT (12%): P${vat.toFixed(2)}`;
    return vat;
}

// Function to calculate discount - update to work with VAT-inclusive prices
function calculateDiscount() {
    const discountOption = document.getElementById("discount-option").value;
    const total = cart.reduce((acc, item) => acc + item.subtotal, 0);
    // No need to add VAT since prices already include VAT
    
    if (discountOption === "senior_citizen" || discountOption === "pwd") {
        return total * 0.20; // 20% of total (already including VAT)
    }
    
    return 0;
}

// Function to show checkout modal and update total including VAT and discount
function showCheckout() {
    if (cart.length === 0) {
        showNotification("Your cart is empty!");
        return;
    }

    const modal = document.getElementById("checkout-modal");
    const orderList = document.getElementById("order-list");
    orderList.innerHTML = "";
    let total = 0;

    // Populate order list and calculate total
    cart.forEach(item => {
        total += item.subtotal;
        orderList.innerHTML += `<li>${item.name} (Qty: ${item.qty}) - P${item.subtotal.toFixed(2)}</li>`;
    });

    // Calculate VAT and discount
    const vat = calculateVat();
    const discount = calculateDiscount();
    
    // No need to add VAT to total since prices already include VAT
    const grandTotal = total - discount;

    document.getElementById("total-amount").innerText = `Total: P${grandTotal.toFixed(2)}`;
    document.getElementById("payment-input").value = "";
    document.getElementById("change-display").innerText = "";

    modal.classList.remove("hidden");
    modal.style.display = "flex";
}

function handleDiscountChange() {
  const selected = document.getElementById("discount-option").value;
  const discountSection = document.getElementById("discount-20-section");

  // Show discount message only if Senior Citizen or PWD is selected
  if (selected === "senior_citizen" || selected === "pwd") {
    discountSection.style.display = "block";
  } else {
    discountSection.style.display = "none";
  }
}

function closeModal() {
    const modal = document.getElementById('checkout-modal');
    modal.style.display = 'none';

    // Reset discount dropdown
    const discountSelect = document.getElementById('discount-option');
    discountSelect.value = 'none';

    // Reset discount display
    const discountAmountElem = document.getElementById('discount-amount');
    discountAmountElem.textContent = '';
    discountAmountElem.classList.add('hidden');

    // Hide the 20% discount applied message
    const discount20Section = document.getElementById('discount-20-section');
    discount20Section.style.display = 'none'; // Hide the discount message

    // Reset total to original
    const totalElem = document.getElementById('total-amount');
    const originalTotal = totalElem.dataset.originalTotal;
    if (originalTotal) {
        totalElem.textContent = `Total: P${parseFloat(originalTotal).toFixed(2)}`;
    }

    // Clear the original total so it reinitializes next time
    delete totalElem.dataset.originalTotal;

    // Recalculate total to apply any changes (e.g., reset discount)
    calculateTotal();
}






// Function to calculate change to display
function calculateChange() {
    const paymentInput = document.getElementById("payment-input").value;
    const payment = parseFloat(paymentInput);

    // Calculate the total (with VAT already included in prices)
    const total = cart.reduce((acc, item) => acc + item.subtotal, 0);
    const discountOption = document.getElementById('discount-option').value;
    let discount = 0;

    if (discountOption === 'senior_citizen' || discountOption === 'pwd') {
        discount = total * 0.20;
    }

    // Compute grand total after discount
    const grandTotal = total - discount;

    // Update the total displayed in the modal
    document.getElementById("total-amount").innerText = `Total: P${grandTotal.toFixed(2)}`;

    // Only calculate and update change if payment is already entered
    if (!isNaN(payment)) {
        if (payment >= grandTotal) {
            const change = payment - grandTotal;
            document.getElementById("change-display").innerText = `Change: P${change.toFixed(2)}`;
        } else {
            document.getElementById("change-display").innerText = "Insufficient payment!";
        }
    } else {
        document.getElementById("change-display").innerText = "Please enter a valid payment!";
    }
}

function calculateTotal() {
    const discountOption = document.getElementById('discount-option').value;
    const discountAmountElem = document.getElementById('discount-amount');
    const totalElem = document.getElementById('total-amount');

    // Store the original total if not already saved
    if (!totalElem.dataset.originalTotal) {
        const currentTotalText = totalElem.textContent.replace(/[^\d.]/g, '');
        totalElem.dataset.originalTotal = parseFloat(currentTotalText).toFixed(2);
    }

    const originalTotal = parseFloat(totalElem.dataset.originalTotal) || 0;
    let discount = 0;

    // Apply 20% discount if applicable
    if (discountOption === 'senior_citizen' || discountOption === 'pwd') {
        discount = originalTotal * 0.20;
        discountAmountElem.textContent = `Discount: -P${discount.toFixed(2)}`;
        discountAmountElem.classList.remove('hidden');
    } else {
        discount = 0;
        discountAmountElem.textContent = ''; // Clear the discount text
        discountAmountElem.classList.add('hidden'); // Ensure the discount element is hidden
    }

    const finalTotal = originalTotal - discount;
    totalElem.textContent = `Total: P${finalTotal.toFixed(2)}`;
}



// Updated processPayment function that now includes order type, VAT, and discount
function processPayment() {
    const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const vat = total * (0.12 / 1.12);
    const discount = calculateDiscount();
    const grandTotal = total - discount;
    const payment = parseFloat(document.getElementById("payment-input").value);
    const orderType = document.getElementById("order-type").value;
    const discountType = document.getElementById("discount-option").value;
    const paymentReferenceInput = document.getElementById("payment-reference");
    const paymentReference = paymentReferenceInput ? paymentReferenceInput.value.trim() : null;
    const paymentMethod = document.getElementById("payment-method")?.value || "cash"; // fallback to "cash" if not provided

    if (isNaN(payment) || payment < grandTotal) {
        showNotification("Invalid or insufficient payment.");
        return;
    }

    fetch("complete_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            cart, 
            payment, 
            vat, 
            discount, 
            grandTotal, 
            order_type: orderType,
            discount_type: discountType,
            payment_reference: paymentReference || null,
            payment_method: paymentMethod
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetch("update_stock.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ cart })
            })
            .then(stockRes => stockRes.json())
            .then(stockData => {
                if (stockData.success) {
                    const change = payment - grandTotal;
                    clearCart();
                    closeModal();
                    window.location.href = `receipt.php?order_id=${data.order_id}&total=${grandTotal}&payment=${payment}&change=${change}&order_type=${orderType}&discount_type=${discountType}`;
                } else {
                    showNotification("Order saved but stock update failed. Please check inventory.", "warning");
                }
            });
        } else {
            showNotification("Failed to complete order: " + (data.error || "Please try again."), "error");
        }
    })
    .catch(error => {
        console.error("Error completing order:", error);
        showNotification("An error occurred during order completion.");
    });
}







        // Function to handle barcode scanning and fetch product details
        function handleBarcodeInput(event) {
            if (event.key === 'Enter') {
                const barcode = event.target.value.trim();
                event.target.value = '';
                if (!barcode) return;
                fetch("fetch_product.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `barcode=${barcode}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showNotification(data.error);
                    } else {
                        addToCart(data.name, parseFloat(data.price), parseInt(data.stock));
                    }
                })
                .catch(error => console.error("Error fetching product:", error));
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            if (typeof renderCart === "function") {
                renderCart();
            }
        });

        function toggleReferenceInput() {
    const method = document.getElementById("payment-method").value;
    const gcashContainer = document.getElementById("gcash-ref-container");

    if (gcashContainer) {
        if (method === "gcash") {
            gcashContainer.classList.remove("hidden");
        } else {
            gcashContainer.classList.add("hidden");
        }
    }
}
 // Reset the payment method to "Cash" and hide the GCash reference input
 function resetPaymentMethod() {
        const paymentMethodSelect = document.getElementById("payment-method");
        paymentMethodSelect.value = "cash"; // Set the payment method back to "Cash"
        toggleReferenceInput(); // Hide the GCash reference input field
    }

    // Initial call to set the GCash reference container visibility based on default payment method
    toggleReferenceInput();


    </script>
</head>
<body class="bg-[#D35400] min-h-screen w-full flex flex-col md:items-stretch">
    <!-- Navigation Bar -->
    <nav class="bg-[url('../indexpic/preview.jpg')] bg-cover bg-center p-4 flex justify-between items-center text-white" style="border-bottom: 5px solid #EC6D10;">
        <img src="../imgmain/logo1.1.png" alt="SukariKo! Logo" class="h-[40px] w-auto pl-10" />
        <div class="nav-links flex items-center space-x-4 pr-10">
            <span class="font-bold text-lg">Admin: <span class="text-[#EC6D10]"><?= htmlspecialchars($user['username']); ?></span></span>
            <a href="/POS/logout.php" class="text-white text-lg font-bold transition hover:text-[#EC6D10]">Logout</a>
        </div>
    </nav>

    <!-- Notification box -->
    <div id="notification" class="fixed top-20 right-5 z-50 px-4 py-2 rounded shadow-lg hidden text-white transition-all duration-300 ease-in-out"></div>

    <div class="flex flex-1">
        <!-- Search Bar -->
        <div class="w-3/5 p-4 bg-[url('../indexpic/preview.jpg')] bg-cover bg-center bg-fixed rounded-lg overflow-hidden">
            <form method="POST" class="flex space-x-2 items-center">
                <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" class="p-2 border border-gray-300 rounded-lg w-96" placeholder="Search for products..." />
                <button type="submit" class="bg-[#ec6d10] text-white p-2 rounded-lg transition hover:bg-[#d45a08]">Search</button>
            </form>
            <br>
            <h2 class="text-lg font-semibold mb-2 text-white">Categories</h2>
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="cashier.php" class="bg-[#E67E22] text-white px-4 py-2 rounded-lg transition hover:bg-[#d45a08] flex-grow text-center">
                    All Products
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="cashier.php?category=<?= htmlspecialchars($category['category_code']); ?>" class="bg-[#E67E22] text-white px-4 py-2 rounded-lg transition hover:bg-[#d45a08] flex-grow text-center">
                        <?= htmlspecialchars($category['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full bg-transparent shadow-md rounded-lg border border-gray-300 table-auto">
                    <thead>
                        <tr class="bg-[#D35400] text-white text-center border-b border-gray-300">
                            <th class="p-2 border border-gray-300">Product</th>
                            <th class="p-2 border border-gray-300">Price</th>
                            <th class="p-2 border border-gray-300">Availability</th>
                            <th class="p-2 border border-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-transparent">
                        <?php foreach ($products as $product): ?>
                            <tr class="text-center border border-gray-300 <?= $product['stock'] == 0 ? 'bg-gray-200' : 'bg-[#FAF3E0]'; ?> bg-transparent">
                                <td class="p-2 border border-gray-300 whitespace-nowrap overflow-hidden text-ellipsis bg-transparent text-white">
                                    <?= htmlspecialchars($product['name']); ?>
                                </td>
                                <td class="p-2 border border-gray-300 bg-transparent text-white">P<?= htmlspecialchars($product['price']); ?></td>
                                <td class="p-2 border border-gray-300 font-semibold <?= $product['stock'] > 0 ? 'text-white' : 'text-red-500'; ?> bg-transparent">
                                    <?= $product['stock'] > 0 ? 'Available: ' . $product['stock'] . ' Orders' : 'Sold Out'; ?>
                                </td>
                                <td class="p-2 border border-gray-300 bg-transparent">
                                <button onclick="addToCart('<?= htmlspecialchars($product['name']); ?>', 
    <?= htmlspecialchars($product['price']); ?>, 
    <?= htmlspecialchars($product['stock']); ?>, 
    <?= htmlspecialchars($product['id']); ?>)" // Add product ID here
    class="bg-[#ec6d10] text-white px-2 py-1 rounded transition hover:bg-[#d55a0d] w-full sm:w-auto <?= $product['stock'] > 0 ? '' : 'opacity-50 cursor-not-allowed'; ?>" 
    <?= $product['stock'] > 0 ? '' : 'disabled'; ?>>
    Add
</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-full md:w-2/5 p-6 bg-[#ec6d10]">
            <h2 class="text-2xl font-semibold mb-2 text-white">Cart</h2>
            <input type="text" id="barcode-input" placeholder="Scan or Enter Barcode" class="w-full px-3 py-2 mb-4 border border-gray-300 rounded" onkeypress="handleBarcodeInput(event)"/>
            <div class="overflow-x-auto">
                <table id="cart-table" class="w-full bg-white shadow-md rounded-lg"></table>
            </div>
            <p id="grand-total" class="text-xl text-white font-semibold mt-4 mb-6">Grand Total: P0.00</p>
            <div class="flex flex-wrap gap-2">
                <button class="bg-red-500 px-4 py-2 text-white rounded hover:bg-red-700 transition w-full md:w-auto border border-white" onclick="clearCart()">Clear Cart</button>
                <button class="bg-[#E67E22] px-4 py-2 text-white rounded hover:bg-[#d45a08] transition w-full md:w-auto border border-white" onclick="showCheckout()">Checkout</button>
            </div>
        </div>
    </div>


<div id="checkout-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-3xl font-bold mb-4">Order Summary</h2>
        <ul id="order-list" class="mb-4"></ul>
        
        <!-- VAT Calculation -->
        <p id="vat-amount" class="mt-2 text-sm font-semibold">VAT (12%): P0.00</p>
        <!-- Discount Display -->
        <p id="discount-amount" class="text-red-600 hidden">Discount: -P0.00</p>
        <!-- Total Amount -->
        <p id="total-amount" class="font-bold text-lg"></p>

        <!-- Discount Option for Senior Citizen/PWD -->
        <label for="discount-option" class="block mt-2 text-sm font-semibold"></label>
        <select id="discount-option" class="border p-2 w-full rounded" onchange="handleDiscountChange(); calculateTotal();">
            <option value="none">None</option>
            <option value="senior_citizen">Senior Citizen</option>
            <option value="pwd">PWD</option>
        </select>

        <!-- This message is hidden by default -->
        <div id="discount-20-section" style="display: none;" class="mt-2 text-green-600 font-medium">
            20% discount applied
        </div>




    <!-- Order Type Selection -->
<label for="order-type" class="block mt-2 text-sm font-semibold">Order Type:</label>
<select id="order-type" class="border p-2 w-full rounded">
    <option value="dine_in">Dine In</option>
    <option value="take_out">Take Out</option>
</select>

    <div class="mt-4">
        <label for="payment-method" class="block font-medium">Payment Method:</label>
        <select id="payment-method" class="w-full p-2 border rounded" onchange="toggleReferenceInput()">
            <option value="cash">Cash</option>
            <option value="gcash">GCash</option>
        </select>
    </div>

    <div id="gcash-ref-container" class="mt-4 hidden">
    <label for="payment-reference" class="block font-medium text-sm text-gray-700">GCash Reference Number:</label>
    <input 
        type="text" 
        id="payment-reference" 
        name="payment-reference"
        class="w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter GCash Ref No." 
        maxlength="20"
        pattern="^[0-9a-zA-Z]{6,20}$"
        title="Enter a valid reference number (6-20 characters)."
        required
    >
</div>


        <!-- Payment Input -->
        <label for="payment-input" class="block mt-2 text-sm font-semibold">Enter Payment:</label>
        <input type="number" id="payment-input" class="border p-2 w-full rounded" min="0" oninput="calculateChange()"/>
        
        <p id="change-display" class="mt-2 text-sm font-semibold"></p>

        <div class="mt-4 flex justify-end space-x-2">
            <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Cancel</button>
            <button onclick="processPayment()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Complete Order</button>
        </div>
    </div>
</div>

</body>
</html>