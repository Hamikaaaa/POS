<?php
require __DIR__ . '/../db.php';

// Fetch categories from the database
$category_query = "SELECT * FROM categories";
$category_result = $conn->query($category_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = floatval($_POST["price"]);
    $stock = intval($_POST["stock"]);
    $barcode = $_POST["barcode"];
    $category_code = $_POST["category_code"];

    // Check if barcode already exists
    $check_sql = "SELECT * FROM products WHERE barcode = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $barcode);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "A product with this barcode already exists!";
    } else {
        $image = $_FILES["image"]["name"];
        $target = "uploads/" . basename($image);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $sql = "INSERT INTO products (name, description, price, image, stock, barcode, category_code) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssis", $name, $description, $price, $image, $stock, $barcode, $category_code);

            if ($stmt->execute()) {
                $success_message = "Product added successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        } else {
            $error_message = "Failed to upload the image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../indexpic/preview.jpg');
            /* Replace with your image path */
            background-size: cover;
            /* Make the image cover the whole background */
            background-repeat: no-repeat;
            /* Prevent image repetition */
            background-position: center;
            /* Center the image */
        }


        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #EC6D10;
            padding: 15px;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar h2 img {
            height: 40px;
            width: auto;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .main-content {
            margin-left: 250px;
            /* Sidebar offset */
            display: flex;
            justify-content: center;
            padding-top: 50px;
            /* optional top spacing */
        }



        .container {

            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin-left: 650px;
        }

        h2 {
            margin-bottom: 20px;
            color: white;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select,
        input[type="file"] {
            padding: 10px;
            border: 1px solid rgba(186, 60, 2, 0.8);
            /* Slightly transparent border */
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fff;
            /* Solid white background */
            color: black;
            /* Black text color for readability */
        }

        /* File input specific styling */
        input[type="file"] {
            background-color: #f9f9f9;
            /* Light background color for file input */
            color: black;
            border: 1px solid rgba(186, 60, 2, 0.8);
            /* Consistent border color */
        }

        /* Webkit Browsers (Chrome, Safari) */
        input[type="file"]::-webkit-file-upload-button {
            background-color: #f9f9f9;
            /* Solid background for the button */
            border: 1px solid #ba3c02;
            /* Border for the button */
            color: black;
            cursor: pointer;
            padding: 5px 10px;
        }

        /* Firefox */
        input[type="file"]::-moz-file-upload-button {
            background-color: #f9f9f9;
            /* Solid background for the button */
            border: 1px solid #ba3c02;
            /* Border for the button */
            color: black;
            cursor: pointer;
            padding: 5px 10px;
        }

        /* Edge */
        input[type="file"]::-ms-file-upload-button {
            background-color: #f9f9f9;
            /* Solid background for the button */
            border: 1px solid #ba3c02;
            /* Border for the button */
            color: black;
            cursor: pointer;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;

        }

        button {
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
            padding: 10px 20px;
            border: none;
            background-color: rgba(186, 60, 2, 1);
            /* Button color */
            color: white;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 10px 0;
            text-align: center;
            /* Align text to the center inside the button */
            width: 100%;
            /* Ensure the button takes full width to center its content */
        }


        button:hover {
            background-color: rgba(186, 60, 2, 0.7);
            /* Lighter color on hover */
        }


        p {
            margin-top: 10px;
            font-weight: bold;
            color: #d9534f;
            /* Red for errors */
        }

        .success {
            color: #5cb85c;
            /* Green for success */
        }

        a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: white;
            /* Initial color */
            transition: color 0.3s;
            /* Smooth transition */
        }

        a:hover {
            color: #bbb;
            /* Darker shade than white, a light gray */
        }
    </style>
</head>

<body>

    <body>
        <div class="sidebar">
            <h2>
                <img src="../imgmain/logo1.png" alt="image">
            </h2>
            <ul>
                <li><a href="super_admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="categories.php"><i class="fas fa-list"></i> Categories</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
                <li><a href="view_inventory.php"><i class="fas fa-warehouse"></i> Inventory</a></li>
                <li><a href="suppliers.php"><i class=" fas fa-address-card"></i>Suppliers</a></li>
                <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
                <li><a href="/POS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="container">
            <h2>Add New Product</h2>

            <!-- Display Success or Error Message -->
            <?php if (isset($error_message)): ?>
                <p class="error"><?= htmlspecialchars($error_message); ?></p>
            <?php elseif (isset($success_message)): ?>
                <p class="success"><?= htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Product Name" required>
                <textarea name="description" placeholder="Product Description"></textarea>
                <input type="number" name="price" placeholder="Price" step="0.01" required>
                <input type="number" name="stock" placeholder="Stock Quantity" required>
                <input type="text" name="barcode" placeholder="Barcode" required>

                <!-- Category Dropdown -->
                <select name="category_code" required>
                    <option value="">Select Category</option>
                    <?php while ($row = $category_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['category_code']); ?>"><?= htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>

                <input type="file" name="image" accept="image/*" required>
                <button type="submit">Add Product</button>
            </form>

            <a href="products.php">Back to Product List</a>
        </div>
    </body>

</html>