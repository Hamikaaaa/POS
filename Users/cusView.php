<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT username FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();

$result = $conn->query("SELECT * FROM products");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SukariKo! - Cashier POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-image: url('../indexpic/ricebg.jpg');
    background-attachment: fixed; /* Makes the background image fixed */
    background-size: cover; /* Ensures the background image covers the whole page */
    min-height: 100vh;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    padding-top: 72px;
        }
        .category-container {
            
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 0px;
            display: flex;
            align-items: center;
        }
        .category-title {
            font-weight: bold;
            margin-left: -10px;
            font-size: 1.2rem;
            color: white;
        }
        .filter-btn {
            background-color: #EC6D10;
    color: white;
    padding: 10px 0; /* uniform vertical padding */
    width: 120px; /* fixed width for all buttons */
    margin: 5px 10px 5px 0;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
    border: 2px solid white;
    outline: none;
    text-align: center;
    margin-left: 15px;
    }

    .filter-btn:hover {
        background-color:#A93404;
    }

    .filter-btn.active {
        background-color: #A93404;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .opacity-50 {
        opacity: 0.5;
    }

    .opacity-50 img {
        pointer-events: none;
    }

    

    

.w-1/4 {
    min-width: 300px; /* Ensures the cart does not shrink too much */
}
/* Modal Background */
#order-summary-modal {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.bg-fixed-image {
    position: relative;
    width: 100%;
    height: 100vh; /* Full screen height */
    background-image: url('../indexpic/preview.jpg'); /* Replace with your image */
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    overflow: hidden;
    
}

.bg-video {
    position: absolute;
    top: 30%;
    left: 50%;
    width: 90%;
    height: 60%;
    transform: translate(-50%, -50%);
    z-index: 1;
    opacity: 1;
    transition: opacity 0.9s ease; 
    
}

.fade-out {
    opacity: 0;
}

.nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    font-size: 30px;
    padding: 10px;
    border: none;
    cursor: pointer;
    z-index: 2;
    border-radius: 50%;
}

.left-5 {
    left: 5%;
}

.right-5 {
    right: 5%;
}

.nav-btn {
    position: absolute;
    top: 30%;
    transform: translateY(-50%);
    background-color: transparent; /* Remove background */
    color: #ec6d10;
    font-size: 50px;
    padding: 12px 20px;
    border-radius: 80%; /* Circular button */
    cursor: pointer;
    z-index: 2;
    transition: background-color 0.3s, border-color 0.3s, transform 0.3s; /* Smooth transition */
}

/* Hover effect */
.nav-btn:hover {
    color: white;
}

.footer {
    background-color: #d65a00;
    color: black;
    text-align: center;
    padding: 30px 0; /* Increased padding for better spacing */
    font-size: 16px;
    position: relative;
    bottom: 0;
    width: 100%;
    height: 30px; /* Adjust the height as needed */
    display: flex;
    justify-content: center;
    align-items: center;
}



    /* Modal z-index styles */
    #address-input-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.75);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }

    #address-input-modal > div {
        background-color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        width: 90%;
        max-width: 500px;
        position: relative;
        transform: none;
        left: auto;
        top: auto;
        margin: auto;
        z-index: 2001;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-error {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: none;
    }

    #order-summary-modal {
        z-index: 1000;
    }

    #order-summary-modal > div {
        z-index: 1001;
    }

    /* Ensure modals don't conflict */
    .modal-open {
        overflow: hidden;
    }


    /* Updated modal styles */
#order-summary-modal > div {
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    margin: 20px;
    width: 800px; /* Fixed width */
    max-width: 90vw; /* Responsive max-width */
}

#order-summary-modal .overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #EC6D10 #F5F5F5;
    direction: ltr;
}



/* Responsive adjustments */
@media (max-width: 868px) {
    #order-summary-modal .grid-cols-2 {
        grid-template-columns: 1fr; /* Stack columns on smaller screens */
    }
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 70;
}

.modal-content {
    background-color: white;
    padding: 2rem;
    border-radius: 0.5rem;
    max-width: 90%;
    max-height: 90%;
    overflow-y: auto;
    z-index: 10000; /* Even higher to be safe */
}

/* Error Message Styling */
.message-container {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    width: 100%;
    max-width: 500px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
}

#message-container {
        z-index: 60; /* Higher than navbar */
    }
    



.message {
    padding: 15px 25px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
    animation: slideDown 0.3s ease-out;
    pointer-events: auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: white;
}

.message-error {
    background-color:rgb(248, 240, 215);
  color: #ec6d10;
}

.message-success {
    background-color:rgb(248, 240, 215);
    color: #ec6d10;
}

.message-warning {
    background-color:rgb(248, 240, 215);
  color: #ec6d10;
}



.message .close-btn {
    margin-left: 15px;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.message .close-btn:hover {
    opacity: 1;
}

@keyframes slideDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

.fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}
     
    </style>
</head>
<body>
    <!-- Message Container -->
<div id="message-container" class="message-container"></div>

<!-- Navbar -->
<nav class="bg-[url('../indexpic/preview.jpg')] bg-cover bg-center p-4 flex justify-between items-center text-white border-b-4 border-[#EC6D10] fixed w-full top-0 z-50"> 
<a href="#" id="scroll-to-top">
    <img src="../imgmain/logo1.1.png" alt="SukariKo! Logo" class="h-[40px] w-auto pl-10" />
</a>

    <div class="nav-links flex items-center space-x-6 pr-10">
    <a href="#walalang">
    <button id="cart-toggle" class="flex items-center space-x-2 text-white hover:text-[#EC6D10] transition">
        <i class="fas fa-shopping-cart text-2xl"></i>
        <span id="cart-count" class="bg-[#EC6D10] text-white rounded-full px-2 py-1 text-xs">0</span>
    </button>
</a>

        <span class="font-bold text-lg">Hello, <span class="text-[#EC6D10]"><?= htmlspecialchars($user['username']); ?></span></span>
        <a href="/POS/logout.php" class="text-white text-lg font-bold transition hover:text-[#EC6D10]">Logout</a>
    </div>
</nav>


<!-- Fixed Background Image Section -->
<div class="bg-fixed-image relative flex flex-col items-start justify-center pt-[60vh]">
    <!-- Previous Button -->
    <button id="prev-btn" class="nav-btn left-5">
    <i class="fas fa-chevron-left" style="font-size: 30px;"></i> <!-- Left Arrow -->
    </button>

    <!-- Video Section -->
    <video id="video-player" autoplay muted loop class="bg-video">
        <source src="../indexpic/savor1.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Next Button -->
    <button id="next-btn" class="nav-btn right-5">
    <i class="fas fa-chevron-right" style="font-size: 30px;"></i>
    </button>

    <!-- Food Categories Title -->
    <h2 class="text-white text-2xl font-bold z-10" style="margin-top: -110px; margin-left: 70px;">Food Categories</h2>

    <!-- Category Images -->
    <div class="flex flex-wrap justify-start mt-6 z-10" style="margin-top: -10px; gap: 5px; margin-left: 20px;">
        <a href="#" class="category-item" style="width: 240px; height: 125px; margin-left: 50px; margin-right: 20px; display: flex; justify-content: center; align-items: center;">
            <img src="../indexpic/riceicon.jpg" alt="Rice Meals" class="object-cover hover:scale-105 transition" style="width: 100%; height: 80%; border-radius: 0px;" />
        </a>
        <a href="#" class="category-item" style="width: 240px; height: 125px; margin-right: 20px; display: flex; justify-content: center; align-items: center;">
            <img src="../indexpic/viandsicon.jpg" alt="Viands" class="object-cover hover:scale-105 transition" style="width: 100%; height: 80%; border-radius: 0px;" />
        </a>
        <a href="#" class="category-item" style="width: 240px; height: 125px; margin-right: 20px; display: flex; justify-content: center; align-items: center;">
            <img src="../indexpic/beveragesicon.jpg" alt="Drinks" class="object-cover hover:scale-105 transition" style="width: 100%; height: 80%; border-radius: 0px;" />
        </a>
        <a href="#" class="category-item" style="width: 240px; height: 125px; display: flex; justify-content: center; align-items: center;">
            <img src="../indexpic/dessertsicon.jpg" alt="Desserts" class="object-cover hover:scale-105 transition" style="width: 100%; height: 80%; border-radius: 0px;" />
        </a>
        <p style="font-size: 17px; margin-left: 25px; margin-top: 10px;" class="text-white">Craving Filipino flavors? Come and taste <br>the best with us!<br><a href="#menu" 
        style="margin-top: -20px; background-color: #ec6d10; color: black; border: 2px solid black; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 5px; margin-left:125px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">Browse Menu</a></p>



        

    </div>
</div>

</div>


<div class="walalang" id="walalang">
    <br><br>
</div>

<!-- Main Container with Dynamic Width -->
<div class="flex transition-all duration-300 ease-in-out" id="main-container" style="position: relative; display: flex; min-height: calc(100vh - 72px); width: 100%;">
    <!-- Product Grid Section (Initially 100%, becomes 60% when cart is shown) -->
    <div id="product-section" class="w-full transition-all duration-300 ease-in-out p-4" style="margin-top: 20px;">
        <!-- Category Filter -->
        <div class="category-container mb-4" id="menu">
            <span class="category-title font-bold">Categories:</span>
            <button class="filter-btn active" onclick="filterCategory('All', this)">All Products</button>
            <button class="filter-btn" onclick="filterCategory('RIC', this)">Rice</button>
            <button class="filter-btn" onclick="filterCategory('VIA', this)">Viands</button>
            <button class="filter-btn" onclick="filterCategory('BEV', this)">Beverages</button>
            <button class="filter-btn" onclick="filterCategory('DES', this)">Desserts</button>
        </div>
    <h2 class="text-xl font-bold mb-4 text-left" style="color: white;">Products</h2>

        
        <!-- Product Grid -->
        <div style="height: 600px; overflow: hidden;">
    <div class="grid grid-cols-4 gap-4 hover:overflow-y-auto" id="product-grid" style="height: 100%; overflow-y: auto; overflow-x: hidden;">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card bg-[#FFE8D6] p-3 shadow-lg rounded-lg text-center flex flex-col items-center space-y-2 transition-transform transform hover:scale-105"
                    data-category="<?= $row['category_code']; ?>">
                    <img src="<?= '../Super_Admin/uploads/' . $row['image']; ?>" 
                        alt="<?= $row['name']; ?>" 
                        class="w-full h-40 object-cover rounded-lg cursor-pointer transition-transform duration-300 hover:scale-110 <?= $row['stock'] == 0 ? 'opacity-50 pointer-events-none' : '' ?>"
                        onclick="openModal('modal-<?= $row['id']; ?>')">
                    <div class="flex justify-between w-full px-2">
                        <h4 class="font-bold text-lg"><?= $row['name']; ?></h4>
                        <p class="text-gray-700 font-semibold">₱<?= number_format($row['price'], 2); ?></p>
                    </div>
                    <p style="color: <?= $row['stock'] == 0 ? 'red' : '#EC6D10'; ?>; font-weight: <?= $row['stock'] == 0 ? 'bold' : '600'; ?>;">
                        <?= $row['stock'] == 0 ? '' : 'Available: ' . $row['stock']; ?>
                    </p>
                    <?php if ($row['stock'] > 0): ?>
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="adjustQuantity('decrease', <?= $row['id']; ?>, <?= $row['stock']; ?>)" 
                                    class="bg-[#EC6D10] text-white w-8 h-8 rounded-l font-bold hover:bg-[#d44606] focus:outline-none">-</button>
                            <input type="number" id="quantity-<?= $row['id']; ?>" 
                                value="1" min="1" max="<?= $row['stock']; ?>" 
                                class="text-center w-12 border border-gray-300 text-lg font-semibold" readonly>
                            <button onclick="adjustQuantity('increase', <?= $row['id']; ?>, <?= $row['stock']; ?>)" 
                                    class="bg-[#EC6D10] text-white w-8 h-8 rounded-r font-bold hover:bg-[#D44606] focus:outline-none">+</button>
                        </div>
                        <button class="bg-[#D44606] text-white px-4 py-2 rounded-lg font-semibold w-full text-center"
                                data-id="<?= $row['id']; ?>" 
                                data-name="<?= $row['name']; ?>" 
                                data-price="<?= $row['price']; ?>"
                                onclick="addToCart(<?= $row['id']; ?>, <?= $row['stock']; ?>)">
                            Add to Cart
                        </button>
                    <?php else: ?>
                        <button class="bg-gray-500 text-white px-4 py-2 rounded-lg cursor-not-allowed w-full text-center" disabled>
                            Unavailable
                        </button>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    </div>
    <!-- Cart Section (Initially hidden) -->
<div id="cart-section" class="hidden w-0 transition-all duration-300 ease-in-out bg-white shadow-lg p-4 h-full overflow-y-auto" style="position: absolute; top: 0;
    right: 0;
    height: 100%;
    background-color: white;
    border-left: 1px solid #e5e7eb;
    overflow-y: auto;
    z-index: 10;
    box-shadow: -2px 0 8px rgba(0,0,0,0.1);
    transition: width 0.3s ease-in-out;">
    
    <h2 class="text-lg font-bold mb-4 flex items-center text-[#EC6D10]" style="margin-top: 20px;">
        <i class="fas fa-shopping-cart mr-2"></i> Cart
    </h2>
    <table class="w-full bg-transparent text-center mb-4 rounded-lg">
        <thead class="bg-[#D44606] text-white">
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="cart-body">
            <!-- Cart items will be dynamically added here -->
        </tbody>
    </table>
    <p class="font-bold text-lg text-[#EC6D10]">Total: ₱<span id="cart-total">0.00</span></p>
    <div id="cart-buttons" class="flex gap-2 mt-4">
        <button id="clear-cart" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-500 flex-1">
            Clear Cart
        </button>
        <button id="checkout-btn" class="bg-[#D44606] text-white px-4 py-2 rounded hover:bg-[#a73705] flex-1">
            Checkout
        </button>
    </div>
</div>
</div>

<footer class="footer">
    <p>&copy; 2025 SukariKo! All Rights Reserved.</p>
</footer>


<!-- Address Input Modal -->
<div id="address-input-modal">
    <div class="bg-white rounded-lg p-6">
        <h3 class="text-xl font-bold mb-4">Enter Delivery Address</h3>
        
        <!-- Add error message container -->
        <div id="address-form-error" class="modal-error mb-4 bg-red-100 text-red-700 p-3 rounded hidden"></div>
        
        <form id="delivery-address-form">
            <!-- Full Name -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="fullName">
                    Full Name*
                </label>
                <input type="text" id="fullName" name="fullName" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Phone Number -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="phoneNumber">
                    Phone Number*
                </label>
                <input type="tel" id="phoneNumber" name="phoneNumber" required
                    pattern="[0-9]{11}" placeholder="11-digit number"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- City/Municipality -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="city">
                    City/Municipality*
                </label>
                <input type="text" id="city" name="city" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Barangay -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="barangay">
                    Barangay*
                </label>
                <input type="text" id="barangay" name="barangay" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Street Address -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="streetAddress">
                    Complete Address*
                </label>
                <textarea id="streetAddress" name="streetAddress" required
                    placeholder="Street Name, Building Name, House No."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500"
                    rows="2"></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-address-btn"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition-colors">
                    Save Address
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Order Summary Modal -->
<div id="order-summary-modal" class="fixed inset-0 bg-[#F5F5F5] bg-opacity-95 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-[800px] max-h-[90vh] flex flex-col border border-gray-300">

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 text-center">Order Summary</h2>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- Product List -->
            <div id="order-products-list" class="space-y-4 mb-6">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">Item</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Quantity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="order-products-body" class="bg-white divide-y divide-gray-200">
                        <!-- Items will be dynamically added here -->
                    </tbody>
                </table>
            </div>

        <!-- Two-column layout for address and payment -->
        <div class="grid grid-cols-2 gap-6 mt-6 p-6">
            <!-- Left Column -->
            <div>
                <!-- Delivery Address Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold text-gray-700">Delivery Address</h3>
                        <button id="add-address-btn" class="text-orange-500 hover:text-orange-600">
                            <i class="fas fa-plus-circle"></i> Add Address
                        </button>
                    </div>
                    <div id="delivery-address-display" class="text-sm text-gray-600">
                        <p class="italic">No delivery address set</p>
                    </div>
                </div>

                <!-- Delivery Fee (Replaces distance input) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">Delivery Fee</h3>
                     <p id="delivery-fee-display" class="text-sm text-gray-600"Delivery fee will be calculated based on your location</p>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Payment Method -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <label class="block font-semibold text-gray-700 mb-2">Payment Method</label>
                    <select id="payment-method" class="w-full p-2 border border-gray-300 rounded">
                        <option value="cod">Cash On Delivery</option>
                        <option value="gcash">GCash</option>
                    </select>
                </div>

                <!-- GCash Payment Section -->
                <div id="gcash-details" class="hidden">
                    <div class="bg-blue-50 p-4 rounded-lg mb-4">
                        <h3 class="font-bold text-blue-800 mb-2">GCash Payment Details</h3>
                        <p class="text-blue-600">Account Name: SukariKo!</p>
                        <p class="text-blue-600">GCash Number: 0991-234-5678</p>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Reference Number</label>
                            <input type="text" id="gcash-reference" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Enter GCash Reference Number">
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Upload Payment Screenshot</label>
                            <input type="file" id="payment_proof" accept="image/*" 
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Footer -->
        <div class="border-t border-gray-200 p-6">
            <!-- Order Summary -->
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-gray-700">
                    <span>Subtotal:</span>
                    <span id="subtotal-amount">P0.00</span>
                </div>
                <div class="flex justify-between text-gray-700">
                    <span>Delivery Fee:</span>
                    <span id="delivery-fee-amount">P0.00</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-gray-700 pt-2 border-t">
                    <span>Total Amount:</span>
                    <span id="total-amount">P0.00</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <button id="cancel-btn" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                    Cancel
                </button>
                <button id="confirm-btn" class="px-6 py-2 text-white rounded-md bg-[#ec6d10] hover:bg-[#d85c0a] transition duration-200">
    Proceed
</button>

            </div>
        </div>
    </div>
</div>


<script>
document.getElementById('scroll-to-top').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent default anchor behavior
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
</script>


<!-- ✅ JavaScript for Quantity Adjustment, Stock Check, and Modal -->
<script>

    const videoPlayer = document.getElementById("video-player");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");

    const videos = [
        "../indexpic/savor1.mp4",
        "../indexpic/savor2.mp4",
        "../indexpic/savor3.mp4",
        "../indexpic/savor4.mp4",
        "../indexpic/savor5.mp4"
    ];

    let currentVideo = 0;

    function changeVideo(index) {
        videoPlayer.classList.add("fade-out"); // Start fading out

        setTimeout(() => {
            videoPlayer.src = videos[index]; // Change the video
            videoPlayer.load(); // Load the new video
            videoPlayer.play(); // Play the new video
            videoPlayer.classList.remove("fade-out"); // Fade in
        }, 300); // Wait for fade-out to complete before changing
    }

    prevBtn.addEventListener("click", () => {
        currentVideo = (currentVideo - 1 + videos.length) % videos.length;
        changeVideo(currentVideo);
    });

    nextBtn.addEventListener("click", () => {
        currentVideo = (currentVideo + 1) % videos.length;
        changeVideo(currentVideo);
    });

    function showMessage(message, type = 'error') {
    const container = document.getElementById('message-container');
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `error-message ${type}-message`;
    
    // Add appropriate icon based on message type
    let icon = '';
    switch(type) {
        case 'success':
            icon = 'check-circle';
            break;
        case 'warning':
            icon = 'exclamation-triangle';
            break;
        case 'error':
            icon = 'times-circle';
            break;
    }
    
    messageDiv.innerHTML = `
        <div class="message-content">
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        </div>
        <span class="close-btn" onclick="closeMessage(this.parentElement)">&times;</span>
    `;
    
    container.appendChild(messageDiv);
    
    // Remove message after 3 seconds
    setTimeout(() => {
        closeMessage(messageDiv);
    }, 3000);
}

function closeMessage(messageElement) {
    messageElement.classList.add('fade-out');
    setTimeout(() => {
        messageElement.remove();
    }, 300);
}



    
function adjustQuantity(action, id, stock) {
    let quantityInput = document.getElementById("quantity-" + id);
    let decreaseBtn = document.getElementById("decrease-btn-" + id);
    let increaseBtn = document.getElementById("increase-btn-" + id);
    let currentValue = parseInt(quantityInput.value);

    if (action === 'increase') {
        if (currentValue < stock) {
            quantityInput.value = currentValue + 1;
        } else {
            showMessage("SukariKo can only cater " + stock + " left.", 'error');
        }
    } else if (action === 'decrease') {
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    // Disable buttons based on stock availability
    decreaseBtn.disabled = quantityInput.value <= 1;
    increaseBtn.disabled = quantityInput.value >= stock;

    // Apply color effects
    updateButtonColors(decreaseBtn, increaseBtn);
}

// Function to update button colors
function updateButtonColors(decreaseBtn, increaseBtn) {
    decreaseBtn.style.backgroundColor = decreaseBtn.disabled ? "#ff4d4d" : "#007bff";
    decreaseBtn.style.color = decreaseBtn.disabled ? "#fff" : "#fff";
    decreaseBtn.style.cursor = decreaseBtn.disabled ? "not-allowed" : "pointer";

    increaseBtn.style.backgroundColor = increaseBtn.disabled ? "#ff4d4d" : "#28a745";
    increaseBtn.style.color = increaseBtn.disabled ? "#fff" : "#fff";
    increaseBtn.style.cursor = increaseBtn.disabled ? "not-allowed" : "pointer";
}


// Alert function for stock warnings
function showMessage(message, type = 'error') {
    const container = document.getElementById('message-container');
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `message message-${type}`;
    
    // Add appropriate icon based on message type
    let icon = '';
    switch(type) {
        case 'success':
            icon = 'check-circle';
            break;
        case 'warning':
            icon = 'exclamation-triangle';
            break;
        case 'error':
            icon = 'times-circle';
            break;
    }
    
    messageDiv.innerHTML = `
        <div class="message-content">
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        </div>
        <span class="close-btn" onclick="closeMessage(this.parentElement)">&times;</span>
    `;
    
    container.appendChild(messageDiv);
    
    // Remove message after 3 seconds
    setTimeout(() => {
        closeMessage(messageDiv);
    }, 3000);
}

function closeMessage(messageElement) {
    messageElement.classList.add('fade-out');
    setTimeout(() => {
        messageElement.remove();
    }, 300);
}


function openModal(id) {
    let modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove("hidden");
    } else {
        console.error("Modal not found:", id);
    }
}

function closeModal(id) {
    let modal = document.getElementById(id);
    if (modal) {
        modal.classList.add("hidden");
    } else {
        console.error("Modal not found:", id);
    }
}


</script>



<script>
// Initialize cart array in localStorage if it doesn't exist
if (!localStorage.getItem('cart')) {
    localStorage.setItem('cart', JSON.stringify([]));
}

// Function to add item to cart
// Modify the addToCart function to include stock information
function addToCart(productId, stockLimit) {
    const quantity = parseInt(document.getElementById(`quantity-${productId}`).value);
    const button = document.querySelector(`[data-id="${productId}"]`);
    const name = button.getAttribute('data-name');
    const price = parseFloat(button.getAttribute('data-price'));

    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        if (existingItem.quantity + quantity > stockLimit) {
            showMessage("Cannot add more items than available in stock");
            return;
        }
        existingItem.quantity += quantity;
    } else {
        if (quantity > stockLimit) {
            showMessage("Cannot add more items than available in stock");
            return;
        }
        cart.push({
            id: productId,
            name: name,
            price: price,
            quantity: quantity,
            stock: stockLimit
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
    updateCartCount(); // Update cart count immediately
    showMessage(`Added ${quantity} ${name} to cart`);
}

// Function to update cart display
function updateCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const cartBody = document.getElementById('cart-body');
    const cartTotal = document.getElementById('cart-total');
    let total = 0;

    // Clear current cart display
    cartBody.innerHTML = '';

    // Add each item to the cart display
    cart.forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        cartBody.innerHTML += `
            <tr class="border-b border-gray-200">
                <td class="py-2">${item.name}</td>
                
                <!-- Quantity Adjusters -->
                <td class="py-2">
                    <div class="flex items-center justify-center space-x-2">
                        <!-- Minus Button -->
                        <button onclick="changeQuantity('decrease', ${item.id}, ${item.quantity}, ${item.stock})" class="bg-[#D44606] text-white w-8 h-8 rounded-l font-bold hover:bg-[#d45b0d] focus:outline-none">-</button>

                        <!-- Quantity Display -->
                        <span class="text-lg font-semibold">${item.quantity}</span>

                        <!-- Plus Button -->
                        <button onclick="changeQuantity('increase', ${item.id}, ${item.quantity}, ${item.stock})" class="bg-[#D44606] text-white w-8 h-8 rounded-r font-bold hover:bg-[#d45b0d] focus:outline-none">+</button>
                    </div>
                </td>

                <td class="py-2">₱${item.price.toFixed(2)}</td>
                <td class="py-2">₱${subtotal.toFixed(2)}</td>
                <td class="py-2">
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    // Update the total
    cartTotal.textContent = total.toFixed(2);



    // Update checkout button state
    const checkoutBtn = document.getElementById('checkout-btn');
    if (cart.length > 0) {
        checkoutBtn.removeAttribute('disabled');
        checkoutBtn.classList.remove('opacity-50');
    } else {
        checkoutBtn.setAttribute('disabled', 'true');
        checkoutBtn.classList.add('opacity-50');
    }
}

// Function to remove item from cart
function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
    updateCartCount(); // Update cart count immediately
    showMessage("Item removed from cart");
}

// Function to clear cart
document.getElementById('clear-cart').addEventListener('click', function() {
    
        localStorage.setItem('cart', JSON.stringify([]));
        updateCartDisplay();
        showMessage("Cart cleared");
    
});

// Initialize cart display when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});
</script>


<!-- Update the JavaScript for cart toggle functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartToggle = document.getElementById('cart-toggle');
    const cartSection = document.getElementById('cart-section');
    const productSection = document.getElementById('product-section');
    const mainContainer = document.getElementById('main-container');
    let isCartVisible = false;

    
    function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalItems;
        
        // Add animation effect
        cartCountElement.classList.add('scale-110');
        setTimeout(() => {
            cartCountElement.classList.remove('scale-110');
        }, 200);
    }
}


    cartToggle.addEventListener('click', function() {
        isCartVisible = !isCartVisible;
        
        if (isCartVisible) {
            // Show cart
            cartSection.classList.remove('hidden');
            cartSection.classList.remove('w-0');
            cartSection.classList.add('w-[40%]');
            
            // Adjust product grid
            productSection.classList.remove('w-full');
            productSection.classList.add('w-[60%]');
            
            // Adjust product grid columns
            const productGrid = document.getElementById('product-grid');
            productGrid.classList.remove('grid-cols-4');
            productGrid.classList.add('grid-cols-3');
            
            // Ensure main container uses flex
            mainContainer.classList.add('flex');
        } else {
            // Hide cart
            cartSection.classList.add('hidden');
            cartSection.classList.add('w-0');
            cartSection.classList.remove('w-[40%]');
            
            // Restore product grid
            productSection.classList.remove('w-[60%]');
            productSection.classList.add('w-full');
            
            // Restore product grid columns
            const productGrid = document.getElementById('product-grid');
            productGrid.classList.remove('grid-cols-3');
            productGrid.classList.add('grid-cols-4');
        }
    });

    // Update cart count on page load and after cart changes
    updateCartCount();
    window.addEventListener('storage', updateCartCount);
});
</script>


















<!-- JavaScript for Filtering -->
<script>
// Function to filter products by category
function filterCategory(category, button) {
    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Add active class to clicked button
    button.classList.add('active');
    
    // Get all product cards
    const products = document.querySelectorAll('.product-card');
    
    // Filter products based on category
    products.forEach(product => {
        if (category === 'All') {
            // Show all products
            product.style.display = 'flex';
            product.classList.remove('hidden');
        } else {
            // Show only products matching the selected category
            const productCategory = product.getAttribute('data-category');
            if (productCategory === category) {
                product.style.display = 'flex';
                product.classList.remove('hidden');
            } else {
                product.style.display = 'none';
                product.classList.add('hidden');
            }
        }
    });
}

// Initialize with "All" category selected
document.addEventListener('DOMContentLoaded', function() {
    // Find the "All Products" button and trigger click
    const allProductsBtn = document.querySelector('.filter-btn');
    if (allProductsBtn) {
        filterCategory('All', allProductsBtn);
    }
});
</script>

<script>
// Constants for delivery fees
const DELIVERY_FEES = {
    'danao': 20,
    'carmen': 20,
    'compostela': 20,
    'liloan': 40,
    'catmon': 40
};

// Global variable for temporary address storage
let tempDeliveryAddress = null;

function changeQuantity(action, id, currentQuantity, stock) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const itemIndex = cart.findIndex(item => item.id === id);

    if (itemIndex === -1) return;

    if (action === 'increase') {
        if (currentQuantity < stock) {
            cart[itemIndex].quantity += 1;
        } else {
            showMessage(`You can only add up to ${stock} items.`);
            return;
        }
    } else if (action === 'decrease') {
        if (currentQuantity > 1) {
            cart[itemIndex].quantity -= 1;
        }
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCart();
    updateCartCount(); // Update cart count immediately
}

// Add event listener for localStorage changes
window.addEventListener('storage', (e) => {
    if (e.key === 'cart') {
        updateCartCount();
    }
});

// Initialize cart count when page loads
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
});



// Function to update the cart display
function updateCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartBody = document.getElementById('cart-body');
    let total = 0;

    if (cart.length === 0) {
        cartBody.innerHTML = '<tr><td colspan="5" class="text-center">Cart is empty</td></tr>';
    } else {
        cartBody.innerHTML = cart.map(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            return `
                <tr class="border-b border-gray-200">
                    <td class="py-2">${item.name}</td>
                    <td class="py-2">
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="changeQuantity('decrease', ${item.id}, ${item.quantity}, ${item.stock})" class="bg-[#EC6D10] text-white w-8 h-8 rounded-l font-bold hover:bg-[#d45b0d] focus:outline-none">-</button>
                            <span class="text-lg font-semibold">${item.quantity}</span>
                            <button onclick="changeQuantity('increase', ${item.id}, ${item.quantity}, ${item.stock})" class="bg-[#EC6D10] text-white w-8 h-8 rounded-r font-bold hover:bg-[#d45b0d] focus:outline-none">+</button>

                        </div>
                    </td>
                    <td class="py-2">₱${item.price.toFixed(2)}</td>
                    <td class="py-2">₱${subtotal.toFixed(2)}</td>
                    <td class="py-2">
                        <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
        }).join('');
    }

    document.getElementById('cart-total').textContent = total.toFixed(2);
}



// Calculate subtotal
function calculateSubtotal() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

function updateOrderSummary() {
    const subtotal = calculateSubtotal();
    document.getElementById('subtotal-amount').textContent = `₱${subtotal.toFixed(2)}`;

    let deliveryFee = 0;
    let totalAmount = subtotal;

    if (tempDeliveryAddress && tempDeliveryAddress.city) {
        const city = tempDeliveryAddress.city.toLowerCase().trim();
        deliveryFee = DELIVERY_FEES[city] || 0;

        const deliveryFeeDisplay = document.getElementById('delivery-fee-display');
        if (!DELIVERY_FEES.hasOwnProperty(city)) {
            deliveryFeeDisplay.innerHTML = '<span class="text-red-600">Sorry, we do not deliver to this location</span>';
            return false;
        }

        deliveryFeeDisplay.textContent = `Delivery Fee: ₱${deliveryFee.toFixed(2)}`;
        totalAmount = subtotal + deliveryFee;
    }

    document.getElementById('delivery-fee-amount').textContent = `₱${deliveryFee.toFixed(2)}`;
    document.getElementById('total-amount').textContent = `₱${totalAmount.toFixed(2)}`;
    return true;
}


// Handle checkout button click
document.getElementById('checkout-btn').addEventListener('click', function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        showMessage('Cart is empty');
        return;
    }

    document.getElementById('order-summary-modal').style.display = 'flex';
    
    // Update product list in order summary
    const orderProductsBody = document.getElementById('order-products-body');
    orderProductsBody.innerHTML = cart.map(item => `
        <tr>
            <td class="px-6 py-4 text-left">${item.name}</td>
            <td class="px-6 py-4 text-center">${item.quantity}</td>
            <td class="px-6 py-4 text-right">₱${item.price.toFixed(2)}</td>
            <td class="px-6 py-4 text-right">₱${(item.price * item.quantity).toFixed(2)}</td>
        </tr>
    `).join('');

    updateOrderSummary();
});

// Handle delivery address form submission
document.getElementById('delivery-address-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const city = formData.get('city').toLowerCase().trim();
    
    // Clear previous error messages
    const errorDiv = document.getElementById('address-form-error');
    errorDiv.style.display = 'none';
    errorDiv.textContent = '';

    // Check if the city is in our delivery coverage
    if (!DELIVERY_FEES.hasOwnProperty(city)) {
        errorDiv.textContent = 'Sorry, we currently do not deliver to this location. Available locations: Danao, Carmen, Compostela, Liloan, and Catmon.';
        errorDiv.style.display = 'block';
        return;
    }

    // If city is valid, proceed with saving the address
    tempDeliveryAddress = {
        fullName: formData.get('fullName'),
        phoneNumber: formData.get('phoneNumber'),
        city: formData.get('city'),
        barangay: formData.get('barangay'),
        streetAddress: formData.get('streetAddress')
    };

    // Update delivery address display
    document.getElementById('delivery-address-display').innerHTML = `
        <p class="font-medium">${tempDeliveryAddress.fullName}</p>
        <p>${tempDeliveryAddress.phoneNumber}</p>
        <p>${tempDeliveryAddress.streetAddress}</p>
        <p>${tempDeliveryAddress.barangay}, ${tempDeliveryAddress.city}</p>
    `;

    // Close modal and update order summary
    document.getElementById('address-input-modal').style.display = 'none';
    updateOrderSummary();
});

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    updateCart();
    
    // Clear cart button
    document.getElementById('clear-cart').addEventListener('click', function() {
        localStorage.removeItem('cart');
        updateCart();
    });

    // Add address button
    document.getElementById('add-address-btn').addEventListener('click', function() {
        document.getElementById('address-input-modal').style.display = 'flex';
    });

    // Cancel buttons
    document.getElementById('cancel-address-btn').addEventListener('click', function() {
        document.getElementById('address-input-modal').style.display = 'none';
    });

    document.getElementById('cancel-btn').addEventListener('click', function() {
        document.getElementById('order-summary-modal').style.display = 'none';
        tempDeliveryAddress = null;
    });

    // Payment method change
    document.getElementById('payment-method').addEventListener('change', function() {
        document.getElementById('gcash-details').style.display = 
            this.value === 'gcash' ? 'block' : 'none';
    });
});



function closeAlert() {
    document.getElementById('alert-box').classList.add('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
</script>

<script>
    // Add this to your existing DOMContentLoaded event listener
    document.getElementById('confirm-btn').addEventListener('click', function() {
    // Check if delivery address exists
    if (!tempDeliveryAddress) {
        showMessage('Please add a delivery address');
        return;
    }

    // Check if valid location for delivery
    if (!updateOrderSummary()) {
        showMessage('We cannot deliver to this location');
        return;
    }

    // For GCash payment, validate reference number
    const paymentMethod = document.getElementById('payment-method').value;
    if (paymentMethod === 'gcash') {
        const reference = document.getElementById('gcash-reference').value;
        if (!reference || reference.trim() === '') {
            showMessage('Please enter GCash reference number');
            return;
        }
    }

    // Prepare order data
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const subtotal = calculateSubtotal();
    const deliveryFee = parseFloat(document.getElementById('delivery-fee-amount').textContent.replace('₱', ''));
    const totalAmount = subtotal + deliveryFee;
    
    const orderData = {
        cart: cart,
        address: tempDeliveryAddress,
        payment: {
            method: paymentMethod,
            reference: paymentMethod === 'gcash' ? document.getElementById('gcash-reference').value : ''
        },
        amounts: {
            subtotal: subtotal,
            deliveryFee: deliveryFee,
            total: totalAmount
        }
    };

    // Set the order data in the hidden input
    const cartDataInput = document.getElementById('cart-data');
    cartDataInput.value = JSON.stringify(orderData);
    
    // Make an AJAX request to deduct stock before confirming the order
    fetch('update_stock.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            cart: orderData.cart
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Change the form action to receipt.php if stock is successfully updated
            document.getElementById('checkout-form').action = "receipt.php";
            
            // Submit the form - this will redirect to receipt.php with the order data
            document.getElementById('checkout-form').submit();
            
            // Clear the cart after successful order
            localStorage.removeItem('cart');
        } else {
            showMessage('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating stock:', error);
        showMessage('There was an error processing your order. Please try again.');
    });
});
</script>

</body>
</html>
