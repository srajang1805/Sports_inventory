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
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing issue id"]);
    exit;
}

$issueId = (int)$data['id'];

// ✅ Update issue status to returned (trigger will increase stock)
$stmt = $conn->prepare("UPDATE Issue SET status = 'returned', return_date = CURDATE() WHERE issue_id = ?");
$stmt->bind_param("i", $issueId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Item returned successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Database update failed", "details" => $conn->error]);
}
?>
