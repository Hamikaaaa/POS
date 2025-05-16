<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>SukariKo!</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: white;
        }

        html {
    scroll-behavior: smooth;
}


        /* Landing Page Background */
        .landing {
            background: url('imgmain/landing1.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
        }

        /* Navbar Styling */
        .navbar {
            position: fixed;
            top: 0px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            z-index: 1000; /* Ensures it's always above other elements */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Transparent black shadow */
            background-color: 0 4px 6px rgba(0, 0, 0, 0.8);
        }

        .navbar img {
            height: 40px;
            width: auto;
            padding-left: 40px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            padding-right: 40px;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 18px;
            color: white;
            transition: color 0.3s ease-in-out;
            font-weight: bold;
        }

        .nav-links a:hover {
            color: #ec6d10;
        }

        /* Content Styling */
        .content {
            max-width: 800px;
            padding: 20px;
            flex-grow: 0.75;
            display: flex;
            justify-content: center;
            align-items: flex-end;
        }

        .bottom-text {
            font-size: 18px;
            line-height: 1.5;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
        }

        /* Explore Button */
        .button {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-signin {
            background-color: black;
            color: #ec6d10;
            border: 2px solid #ec6d10;
        }

        .btn:hover {
            opacity: 0.6;
        }

        /* Preview Section */
    .preview {
        position: relative;
        height: 100vh;
        background: url('indexpic/preview3.jpg') no-repeat center center/cover;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    /* Categories Section */
    .categories {
        position: absolute;
        bottom: 20px; /* Places it at the bottom */
        width: 100%;
        display: flex;
        justify-content: center;
        gap: 10px; /* Reduced gap to compress the items */
    }

    .category-header {
        position: sticky;
        top: 0;
        padding: 10px;
        z-index: 100;
        display: flex;
        align-items: center;
}

    .category-item {
        position: relative;
        width: 300px; /* Adjusted size to fit better */
        height: 200px;
        overflow: hidden;
        border-radius: 5px;
        cursor: pointer;
    }

    .category-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s ease-in-out;
    }

    .category-item:hover img {
        filter: brightness(0.6);
    }

    .hidden {
        display: none !important;
    }

    .back-btn {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.back-btn svg {
    width: 30px;
    height: 30px;
    transition: transform 0.2s ease-in-out;
}

.back-btn:hover svg {
    transform: scale(1.1);
}

    
/* Product Showcase Section */
.product-showcase {
        background: url('indexpic/ricebg.jpg') no-repeat center center/cover;
        background-attachment: fixed; /* Keeps the background fixed while scrolling */
        padding: 50px 20px;
        color: black;
        overflow: hidden; /* Hides both horizontal and vertical scrollbars */
        -ms-overflow-style: none;  /* Hides scrollbar for Internet Explorer and Edge */
    scrollbar-width: none;  /* Hides scrollbar for Firefox */
}

.product-showcase::-webkit-scrollbar {
    display: none; /* Hides scrollbar for WebKit browsers (Chrome, Safari, Edge) */
}


.category-title {
    font-size: 28px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

.product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        padding: 20px;
        justify-content: center;
        overflow-y: visible; /* Allows scrolling for products */
        max-height: fit-content; /* Keeps within screen space */
    }

.product-item {
    background: #FFE8D6;
    padding: 20px;
    border-radius: 5px;
    transition: 0.3s ease-in-out;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
}

.product-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: 5px 10px;
    box-sizing: border-box;
}

.product-info h3 {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin: 0;
    flex: 1; /* Ensures it fills available space */
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-info .price {
    font-size: 18px;
    font-weight: bold;
    color: #ec6d10;
    margin: 0;
    flex-shrink: 0; /* Prevents shrinking */
    text-align: right;
    min-width: 70px; /* Ensures consistent alignment */
}

.product-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 5px;
    transition: 0.3s ease-in-out;
}

.product-item:hover img {
    transform: scale(1.05);
}

.product-item h3 {
    font-size: 20px;
    margin: 15px 0 8px;
    color: #333;
}

.product-item .price {
    font-size: 18px;
    font-weight: bold;
    color: #ec6d10;
    margin-bottom: 10px;
}

.product-item .description {
    font-size: 14px;
    color: #666;
}

.product-item:hover {
    transform: translateY(-5px); /* Hover animation */
    box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.15);
}

/* Buy Button */
.buy-btn {
    background-color: #ec6d10;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s ease-in-out;
}

.buy-btn:hover {
    background-color: #d65a00;
    transform: scale(1.05);
}

/* Footer Styling */
.footer {
    border-top: 3px black solid;
    background-color: #ec6d10;
    color: white;
    padding: 10px 20px;
    text-align: center;
    font-size: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.footer .footer-links {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.footer .footer-links a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.footer .footer-links a:hover {
    color: black;
}

.footer .social-icons {
    margin-top: 2px;
}

.footer .social-icons a {
    margin: 0 8px;
    color: white;
    font-size: 20px;
    transition: color 0.3s ease;
}

.footer .social-icons a:hover {
    color: black;
}

.footer .copyright {
    font-size: 14px;
    margin-top: 2px;
}

/* page animation styles */
#fade-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7); /* black with transparency */
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.1s;
        z-index: 9999;
    }

    #fade-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }






   
    </style>
</head>
<body>

    <!-- Landing Section -->
    <div class="landing">
       <!-- Navbar -->
<div class="navbar" id="navbar">
    <a href="landing.php">
        <img src="imgmain/logo1.1.png" alt="SukariKo Logo">
    </a>
    <div class="nav-links">
        <a href="landing.php">Home</a>
        <a href="aboutUs.php">About Us</a>
        <a href="contactUs.php">Contact Us</a>
        <a href="index.php">Signin</a>
        <a href="signup.php">Signup</a>
    </div>
</div>


        <!-- Bottom Text -->
        <div class="content">
            <p class="bottom-text">
                <br>
                SukariKo! serves authentic Filipino rice meals,<br>
                savory viands, delightful desserts, and refreshing <br>
                beverages, bringing the taste of the Philippines <br>
                to your table.
            </p>
        </div>

        <!-- Explore Button -->
        <div class="button">
            <button class="btn btn-signin" onclick="scrollToPreview()">Explore</button>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="preview">
       

        <!-- Categories Section -->
        <div class="categories">
        <?php
$conn = new mysqli("localhost", "root", "", "pos_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);

if ($category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        echo '
        <div class="category-item">
            <a href="#' . strtolower($row['name']) . '" onclick="filterCategory(\'' . strtolower($row['name']) . '\')">
                <img src="' . $row['img'] . '" alt="' . htmlspecialchars($row['name']) . '">
            </a>
        </div>';
    }
}
?>


</div>


       
    </div>

    <!-- Product List Showcase -->
<!-- Product List Showcase -->
<div class="product-showcase">

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "pos_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all categories from the database
$categoryQuery = "SELECT * FROM categories"; // Assuming you have a 'categories' table
$categoryResult = $conn->query($categoryQuery);

if ($categoryResult->num_rows > 0) {
    // Loop through each category and fetch associated products
    while ($category = $categoryResult->fetch_assoc()) {
        $categoryName = $category['name'];
        $categoryCode = $category['category_code']; // Assuming category table has 'code' and 'name'

        echo '<div class="category-header" data-category="' . strtolower($categoryName) . '">';
        echo '<button class="back-btn" onclick="scrollToPreview()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 19L8 12L15 5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>';
        echo "<h2 id='" . strtolower($categoryName) . "' class='category-title'>$categoryName</h2>";
        echo '</div>'; // Close category-header div

        echo "<div class='product-grid' data-category='" . strtolower($categoryName) . "'>";

        // Fetch products for the current category based on category_code
        $productQuery = "SELECT * FROM products WHERE category_code = '$categoryCode'";
        $productResult = $conn->query($productQuery);

        if ($productResult->num_rows > 0) {
            // Loop through the products and display them
            while ($product = $productResult->fetch_assoc()) {
                echo "<div class='product-item'>";
                echo "<img src='Super_Admin/uploads/" . $product['image'] . "' alt='" . $product['name'] . "'>";
                echo "<div class='product-info'>";
                echo "<h3>" . $product['name'] . "</h3>";
                echo "<p class='price'>₱" . number_format($product['price'], 2) . "</p>";
                echo "</div>";
                echo "<p class='description'>" . $product['description'] . "</p>";
                echo "<button class='buy-btn' onclick=\"window.location.href='index.php'\">Buy Now</button>";
                echo "</div>";
            }
        } else {
            echo "<p>No products available in this category.</p>";
        }

        echo "</div>"; // Close product-grid
    }
} else {
    echo "<p>No categories available.</p>";
}

// Close database connection
$conn->close();
?>

  



    </div>


   


    <script>
let fromTop = false;

// Scroll to preview section
function scrollToPreview() {
    fromTop = true;
    document.querySelector('.preview').scrollIntoView({ behavior: 'smooth' });
}

// Show selected category and hide others
function filterCategory(category) {
    const showcase = document.querySelector('.product-showcase');
    if (!fromTop) return; // Prevent showing unless came from top

    // Hide all other category headers and grids
    document.querySelectorAll('.category-header, .product-grid').forEach(el => {
        el.classList.add('hidden');
    });

    // Show only the selected category
    document.querySelectorAll(`[data-category="${category}"]`).forEach(el => {
        el.classList.remove('hidden');
    });

    // Scroll to product showcase
    showcase.scrollIntoView({ behavior: 'smooth' });
}

// Allow scroll back to preview and reset view
function scrollToPreview() {
    fromTop = true;

    // Show all categories again
    document.querySelectorAll('.category-header, .product-grid').forEach(el => {
        el.classList.remove('hidden');
    });

    document.querySelector('.preview').scrollIntoView({ behavior: 'smooth' });
}

window.addEventListener("scroll", function () {
        var navbar = document.getElementById("navbar");
        var previewSection = document.querySelector(".preview");
        var productSection = document.querySelector(".product-showcase");

        if (window.scrollY >= previewSection.offsetTop + previewSection.offsetHeight) {
            navbar.classList.add("hidden"); // Hide navbar in product list
        } else {
            navbar.classList.remove("hidden"); // Show navbar on landing & preview
        }
    });


    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll(".nav-links a, .navbar a[href='landing.php']");
        const overlay = document.getElementById("fade-overlay");

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                const href = this.getAttribute("href");
                if (href && href !== "#") {
                    e.preventDefault(); // Prevent immediate navigation
                    overlay.classList.add("active"); // Trigger fade to black
                    setTimeout(() => {
                        window.location.href = href;
                    }, 100); // Match the CSS transition duration
                }
            });
        });
    });

</script>





<!-- Footer Section -->
<!-- Footer Section -->
<div class="footer">
    <div class="footer-links">
        <a href="landing.php">Home</a>
        <a href="aboutUs.php">About Us</a>
        <a href="contactUs.php">Contact Us</a>
        <a href="index.php">Signin</a>
        <a href="signup.php">Signup</a>
    </div>
    <div class="social-icons">
        <a href="https://www.facebook.com/montegrande11" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.facebook.com/montegrande11" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="https://www.facebook.com/montegrande11" title="Twitter"><i class="fab fa-twitter"></i></a>
    </div>
    <div class="copyright">
        &copy; 2025 SukariKo! All rights reserved.
    </div>
</div>


<div id="fade-overlay"></div>

</body>

</html>
