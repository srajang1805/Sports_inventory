<?php
require_once __DIR__ . '/../cors.php';
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../utils/verify_jwt.php';

header("Content-Type: application/json");

// Auth check (optional)
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

if (!$token || !verifyJWT($token)) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // ✅ Fetch all available items
    $sql = "SELECT i.item_id, i.item_name, i.brand, i.quantity, 
                   i.item_condition, i.purchase_date, s.sport_name
            FROM Item i
            LEFT JOIN Sport s ON i.sport_id = s.sport_id
            ORDER BY i.item_name ASC";

    $result = $conn->query($sql);
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode($items);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
?>
