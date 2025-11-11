<?php
require_once "../cors.php";
require_once "../db_connect.php";
require_once "../utils/generate_jwt.php";
header("Content-Type: application/json");

// Read JSON safely
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

// Handle invalid/missing input
if (!$data || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input: please send JSON with email and password"]);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid credentials"]);
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid credentials"]);
        exit;
    }

    // Generate JWT
    $token = generateJWT($user['id'], $user['email']);
    echo json_encode(["token" => $token]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error", "details" => $e->getMessage()]);
}
?>
