<?php
require_once __DIR__ . '/../cors.php';
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../utils/verify_jwt.php';

header("Content-Type: application/json");

// JWT Auth
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

if (!$token || !verifyJWT($token)) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // ✅ Fetch currently issued items
    $sql = "SELECT i.issue_id, it.item_name, i.quantity_issued, i.issue_date, i.status
            FROM Issue i
            JOIN Item it ON i.item_id = it.item_id
            ORDER BY i.issue_date DESC";
    $result = $conn->query($sql);

    $issues = [];
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
    }

    echo json_encode($issues);
    exit;
}

if ($method === 'POST') {
    // ✅ Issue new item
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['itemId']) || !isset($data['quantityIssued'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    $itemId = (int)$data['itemId'];
    $qty = (int)$data['quantityIssued'];
    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO Issue (item_id, issue_date, quantity_issued, status) VALUES (?, ?, ?, 'issued')");
    $stmt->bind_param("isi", $itemId, $date, $qty);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Item issued successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database error", "details" => $conn->error]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
?>
