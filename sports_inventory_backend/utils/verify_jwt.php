<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once __DIR__ . '/../vendor/autoload.php';

function verifyJWT($token) {
  $key = "secret_key_here";
  try {
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    return $decoded;
  } catch (Exception $e) {
    return false;
  }
}
?>
