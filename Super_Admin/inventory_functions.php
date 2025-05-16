<?php
require_once __DIR__ . '/../db.php';

function deductRecipeIngredients($recipe_id, $quantity = 1)
{
    global $pdo;

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Get all ingredients for the recipe
        $stmt = $pdo->prepare("
            SELECT ri.item_id, ri.quantity * ? as needed_quantity, ii.item_name, ii.current_stock
            FROM recipe_ingredients ri
            JOIN inventory_items ii ON ri.item_id = ii.id
            WHERE ri.recipe_id = ?
        ");
        $stmt->execute([$quantity, $recipe_id]);
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // First check if all ingredients are available
        foreach ($ingredients as $ing) {
            if ($ing['current_stock'] < $ing['needed_quantity']) {
                throw new Exception("Insufficient stock for {$ing['item_name']}. Needed: {$ing['needed_quantity']}, Available: {$ing['current_stock']}");
            }
        }

        // Now deduct each ingredient
        foreach ($ingredients as $ing) {
            // Update inventory
            $update = $pdo->prepare("
                UPDATE inventory_items 
                SET current_stock = current_stock - ? 
                WHERE id = ? AND current_stock >= ?
            ");
            $update->execute([$ing['needed_quantity'], $ing['item_id'], $ing['needed_quantity']]);

            if ($update->rowCount() === 0) {
                throw new Exception("Failed to update inventory for {$ing['item_name']}");
            }

            // Log transaction
            $log = $pdo->prepare("
                INSERT INTO inventory_transactions 
                (item_id, quantity, transaction_type, reference_id)
                VALUES (?, ?, 'recipe_usage', ?)
            ");
            $log->execute([$ing['item_id'], $ing['needed_quantity'], $recipe_id]);
        }

        // Commit transaction
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Inventory deduction failed: " . $e->getMessage());
        return false;
    }
}