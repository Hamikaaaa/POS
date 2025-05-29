<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: inventory.php");
    exit();
}

// Fetch current inventory item
$stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Item not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];

    $update = $pdo->prepare("UPDATE inventory SET quantity = ?, location = ?, last_updated = NOW() WHERE id = ?");
    $update->execute([$quantity, $location, $id]);

    header("Location: inventory.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Inventory</title>
    <style>
        body {
            font-family: Arial;
            background: #F4F6F9;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #EC6D10;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #EC6D10;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #d45a00;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Edit Inventory</h2>
        <form method="POST">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0" required>

            <label for="location">Location</label>
            <input type="text" name="location" value="<?= htmlspecialchars($item['location']) ?>" required>

            <button type="submit">Update Inventory</button>
        </form>
    </div>
</body>

</html>