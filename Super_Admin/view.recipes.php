<?php
include_once(__DIR__ . '/../db.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /POS/login.php');
    exit();
}

// Delete recipe if requested
if (isset($_GET['delete'])) {
    $recipe_id = $_GET['delete'];
    // First delete the ingredients to maintain referential integrity
    $pdo->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?")->execute([$recipe_id]);
    // Then delete the recipe
    $pdo->prepare("DELETE FROM recipes WHERE id = ?")->execute([$recipe_id]);
    $_SESSION['message'] = "Recipe deleted successfully.";
    header("Location: view_recipes.php");
    exit();
}

// Handle recipe update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_recipe'])) {
    $recipe_id = $_POST['recipe_id'];
    $recipe_name = $_POST['recipe_name'];

    // Update recipe name
    $pdo->prepare("UPDATE recipes SET recipe_name = ? WHERE id = ?")->execute([$recipe_name, $recipe_id]);

    // Delete existing ingredients
    $pdo->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?")->execute([$recipe_id]);

    // Add new ingredients
    if (isset($_POST['ingredients'])) {
        foreach ($_POST['ingredients'] as $ingredient) {
            $item_id = $ingredient['item_id'];
            $quantity = $ingredient['quantity'];
            $unit = $ingredient['unit'];

            $pdo->prepare("INSERT INTO recipe_ingredients (recipe_id, item_id, quantity, unit) VALUES (?, ?, ?, ?)")
                ->execute([$recipe_id, $item_id, $quantity, $unit]);
        }
    }

    $_SESSION['message'] = "Recipe updated successfully.";
    header("Location: view_recipes.php");
    exit();
}

// Fetch all recipes
$recipes = $pdo->query("SELECT * FROM recipes")->fetchAll();

// Fetch all inventory items for the edit form
$inventory_items = $pdo->query("SELECT id, item_name FROM inventory_items")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F4F6F9;
            display: flex;
            min-height: 100vh;
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


        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 250px;
            /* Same as sidebar width */
            padding: 30px;
            background: white;
            min-height: 100vh;
        }

        /* Container within main content */
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Recipe Card Styles */
        .recipe-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .recipe-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .recipe-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        /* Action Buttons */
        .action-buttons a {
            margin-left: 10px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .edit-btn {
            color: #11998e;
        }

        .delete-btn {
            color: #e74c3c;
        }

        /* Ingredients List */
        .ingredients-list {
            list-style-type: none;
            padding: 0;
            margin: 15px 0 0 0;
        }

        .ingredients-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        /* Edit Form Styles */
        .edit-form {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        /* Ingredient Row Styles */
        .ingredient-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .ingredient-row select {
            flex: 2;
        }

        .ingredient-row input[type="number"] {
            flex: 1;
        }

        .ingredient-row input[type="text"] {
            flex: 1;
        }

        /* Button Styles */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #11998e;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0d7a6f;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-add {
            display: inline-block;
            background-color: #EC6D10;
            color: white;
            padding: 10px 20px;
            margin: 20px 0;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-add:hover {
            background-color: #d45d0a;
            color: white;
        }

        /* Message Styles */
        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Navigation Links */
        .nav-links {
            margin-top: 20px;
        }

        .nav-links a {
            margin-right: 15px;
            text-decoration: none;
            color: #11998e;
        }

        h1,
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2><img src="../imgmain/logo1.png" alt="POS Logo"></h2>
        <ul>
            <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="categories.php"><i class="fas fa-list"></i> Categories</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="view_recipes.php" class="active"><i class="fas fa-utensils"></i> Recipes</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="/POS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content-container">
            <h1>All Recipes</h1>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <a href="add_recipe.php" class="btn-add">
                <i class="fas fa-plus"></i> Add New Recipe
            </a>

            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <div class="recipe-header">
                        <h2 class="recipe-title"><?= htmlspecialchars($recipe['recipe_name']) ?></h2>
                        <div class="action-buttons">
                            <a href="#" class="edit-btn" onclick="toggleEditForm(<?= $recipe['id'] ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="?delete=<?= $recipe['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this recipe?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>

                    <h3>Ingredients</h3>
                    <ul class="ingredients-list">
                        <?php
                        $stmt = $pdo->prepare("
                            SELECT ri.quantity, ri.unit, ii.item_name
                            FROM recipe_ingredients ri
                            JOIN inventory_items ii ON ri.item_id = ii.id
                            WHERE ri.recipe_id = ?
                        ");
                        $stmt->execute([$recipe['id']]);
                        $ingredients = $stmt->fetchAll();

                        foreach ($ingredients as $ing) {
                            echo "<li>" . htmlspecialchars($ing['item_name']) . " - " . htmlspecialchars($ing['quantity']) . " " . htmlspecialchars($ing['unit']) . "</li>";
                        }
                        ?>
                    </ul>

                    <!-- Edit Form (hidden by default) -->
                    <div id="edit-form-<?= $recipe['id'] ?>" class="edit-form">
                        <form method="post" action="">
                            <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
                            <input type="hidden" name="update_recipe" value="1">

                            <div class="form-group">
                                <label for="recipe_name">Recipe Name:</label>
                                <input type="text" class="form-control" id="recipe_name" name="recipe_name"
                                    value="<?= htmlspecialchars($recipe['recipe_name']) ?>" required>
                            </div>
                            <h3>Ingredients</h3>
                            <div id="ingredients-container-<?= $recipe['id'] ?>">
                                <?php foreach ($ingredients as $index => $ing): ?>
                                    <?php
                                    // Safely set fallback values if keys are missing
                                    $item_id = isset($ing['item_id']) ? $ing['item_id'] : '';
                                    $quantity = isset($ing['quantity']) ? $ing['quantity'] : '';
                                    $unit = isset($ing['unit']) ? $ing['unit'] : '';
                                    ?>
                                    <div class="ingredient-row">
                                        <select name="ingredients[<?= $index ?>][item_id]" class="form-control" required>
                                            <option value="">-- Select Item --</option>
                                            <?php foreach ($inventory_items as $item): ?>
                                                <option value="<?= $item['id'] ?>" <?= $item['id'] == $item_id ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($item['item_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <input type="number" class="form-control"
                                            name="ingredients[<?= $index ?>][quantity]"
                                            value="<?= htmlspecialchars($quantity) ?>" step="0.01" min="0" required>

                                        <input type="text" class="form-control"
                                            name="ingredients[<?= $index ?>][unit]"
                                            value="<?= htmlspecialchars($unit) ?>" required>

                                        <button type="button" class="btn btn-danger" onclick="removeIngredientRow(this)">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>


                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="addIngredientRow(<?= $recipe['id'] ?>)">
                                    <i class="fas fa-plus"></i> Add Ingredient
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Recipe
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?= $recipe['id'] ?>)">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <script>
        // Toggle edit form visibility
        function toggleEditForm(recipeId) {
            const form = document.getElementById(`edit-form-${recipeId}`);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        // Add new ingredient row
        function addIngredientRow(recipeId) {
            const container = document.getElementById(`ingredients-container-${recipeId}`);
            const index = container.querySelectorAll('.ingredient-row').length;

            const row = document.createElement('div');
            row.className = 'ingredient-row';

            row.innerHTML = `
                <select name="ingredients[${index}][item_id]" class="form-control" required>
                    <?php foreach ($inventory_items as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['item_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" class="form-control" name="ingredients[${index}][quantity]" step="0.01" min="0" required>
                <input type="text" class="form-control" name="ingredients[${index}][unit]" required>
                <button type="button" class="btn btn-danger" onclick="removeIngredientRow(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            `;

            container.appendChild(row);
        }

        // Remove ingredient row
        function removeIngredientRow(button) {
            button.closest('.ingredient-row').remove();
            // Reindex the remaining ingredients
            const container = button.closest('div[id^="ingredients-container-"]');
            if (container) {
                const rows = container.querySelectorAll('.ingredient-row');
                rows.forEach((row, index) => {
                    row.querySelector('select').name = `ingredients[${index}][item_id]`;
                    row.querySelector('input[type="number"]').name = `ingredients[${index}][quantity]`;
                    row.querySelector('input[type="text"]').name = `ingredients[${index}][unit]`;
                });
            }
        }
    </script>
</body>

</html>