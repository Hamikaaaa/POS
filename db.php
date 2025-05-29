<?php
// $host = 'localhost'; // Change if using a different server
// $dbname = 'pos_system'; // Make sure this matches your database
// $username = 'root'; // Default XAMPP MySQL user
// $password = ''; // Default XAMPP MySQL password (empty)

$host = 'localhost'; // Change if using a different server
$dbname = 'u866427573_sukariko'; // Make sure this matches your database
$username = 'u866427573_sukariko'; // Default XAMPP MySQL user
$password = '@Qetu1357'; // Default XAMPP MySQL password (empty)

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Set default fetch mode
            PDO::ATTR_EMULATE_PREPARES => false // Prevent SQL injection
        ]
    );

    // Ensure compatibility with scripts using MySQLi ($conn)
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("MySQLi connection failed: " . $conn->connect_error);
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
