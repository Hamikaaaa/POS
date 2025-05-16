<?php
include_once(__DIR__ . '/../db.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_name = $_POST['recipe_name'];
    $ingredients = $_POST['ingredients'];

    // Insert into recipes table
    $stmt = $pdo->prepare("INSERT INTO recipes (recipe_name) VALUES (?)");
    $stmt->execute([$recipe_name]);
    $recipe_id = $pdo->lastInsertId();  // Get the last inserted ID

    // Insert each ingredient
    $stmt2 = $pdo->prepare("INSERT INTO recipe_ingredients (recipe_id, item_id, quantity, unit) VALUES (?, ?, ?, ?)");
    foreach ($ingredients as $ingredient) {
        $stmt2->execute([
            $recipe_id,
            $ingredient['item_id'],
            $ingredient['quantity'],
            $ingredient['unit']
        ]);
    }

    echo "<p style='color: green;'>✅ Recipe added successfully!</p>";
}


// Fetch inventory items
$inventory_items = $pdo->query("SELECT id, item_name, unit FROM inventory_items")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
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

        .container {
            max-width: 900px;
            margin-left: 270px;
            /* Account for sidebar */
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .buttons {
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 15px;
            background: #11998e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            border: none;
        }

        .btn:hover {
            background: #38ef7d;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .ingredient-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
        }

        .ingredient-row select,
        .ingredient-row input[type="number"],
        .ingredient-row input[type="text"] {
            flex: 1;
            margin-bottom: 0;
        }

        .add-item-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #EC6D10;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            font-weight: bold;
        }

        .add-item-btn:hover {
            background: #FF8C42;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>
            <img src="../imgmain/logo1.png" alt="image">
        </h2>
        <ul>
            <li><a href="super_admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="categories.php"><i class="fas fa-list"></i> Categories</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="sales.php"><i class="fas fa-dollar-sign"></i> Sales</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="suppliers.php"><i class=" fas fa-address-card"></i>Suppliers</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="/POS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>Add Recipe</h2>

        <form method="POST">
            <label>Recipe Name:</label>
            <input type="text" name="recipe_name" required>

            <h3>Ingredients</h3>
            <div id="ingredients-container">
                <div class="ingredient-row">
                    <select name="ingredients[0][item_id]" required>
                        <option value="">-- Select Ingredient --</option>
                        <?php foreach ($inventory_items as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['item_name']) ?> (<?= $item['unit'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="ingredients[0][quantity]" step="0.01" placeholder="Quantity" required>
                    <input type="text" name="ingredients[0][unit]" placeholder="Unit" required>
                </div>
            </div>

            <button type="button" class="btn" onclick="addIngredient()">➕ Add Ingredient</button>
            <button type="submit" class="btn">✅ Save Recipe</button>
        </form>

        <div class="buttons">
            <a href="view_recipes.php" class="add-item-btn">Back to Recipes</a>
            <a href="super_admin_dashboard.php" class="add-item-btn">Back to Dashboard</a>
        </div>
    </div>

    <script>
        let ingredientIndex = 1;

        function addIngredient() {
            const container = document.getElementById('ingredients-container');
            const newRow = document.createElement('div');
            newRow.className = 'ingredient-row';

            newRow.innerHTML = `
                <select name="ingredients[${ingredientIndex}][item_id]" required>
                    <option value="">-- Select Ingredient --</option>
                    <?php foreach ($inventory_items as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['item_name']) ?> (<?= $item['unit'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="ingredients[${ingredientIndex}][quantity]" step="0.01" placeholder="Quantity" required>
                <input type="text" name="ingredients[${ingredientIndex}][unit]" placeholder="Unit" required>
            `;
            container.appendChild(newRow);
            ingredientIndex++;
        }
    </script>
</body>

</html>