<?php
session_start();

// Check if the user is logged in and their role is 'user'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    // If not logged in or not a user, redirect to login page
    header('Location: login.php');
    exit();
}

// Get the logged-in user's information
require 'db.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E8EFCF;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-box {
            text-align: center;
            padding: 30px;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .dashboard-box h2 {
            margin-bottom: 20px;
            color: #DBA979;
        }

        .dashboard-box p {
            color: #333;
            margin: 10px 0;
        }

        .logout-btn {
            padding: 10px;
            background-color: #AFD198;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background-color: #DBA979;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <div class="dashboard-box">
            <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
            <p>Role: <?php echo ucfirst($user['role']); ?></p>

            <form action="logout.php" method="POST">
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        </div>
    </div>

</body>
</html>
