<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required = ['fullName', 'phoneNumber', 'city', 'barangay', 'streetAddress'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    $userId = $_SESSION['user_id'];

    // Insert or update delivery address
    $sql = "INSERT INTO delivery_addresses 
            (user_id, full_name, phone_number, city, barangay, street_address, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            full_name = VALUES(full_name),
            phone_number = VALUES(phone_number),
            city = VALUES(city),
            barangay = VALUES(barangay),
            street_address = VALUES(street_address),
            updated_at = NOW()";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $userId,
        $data['fullName'],
        $data['phoneNumber'],
        $data['city'],
        $data['barangay'],
        $data['streetAddress']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Address saved successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
