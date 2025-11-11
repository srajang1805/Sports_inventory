<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once __DIR__ . '/../vendor/autoload.php';

function generateJWT($userId, $email) {
  $key = "secret_key_here";
  $payload = [
    "id" => $userId,
    "email" => $email,
    "iat" => time(),
    "exp" => time() + (60*60) // 1 hour expiry
  ];
  return JWT::encode($payload, $key, 'HS256');
}
?>
