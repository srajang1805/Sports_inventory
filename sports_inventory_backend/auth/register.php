<?php
require_once "../cors.php";
require_once "../db_connect.php";
require_once "../utils/generate_jwt.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$name = trim($data['name']);
$email = trim($data['email']);
$password = password_hash(trim($data['password']), PASSWORD_BCRYPT);

// Check for existing email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    echo json_encode(["error" => "Email already registered"]);
    exit;
}

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);
$stmt->execute();

$id = $conn->insert_id;
$token = generateJWT($id, $email);

echo json_encode(["token" => $token]);
?>
