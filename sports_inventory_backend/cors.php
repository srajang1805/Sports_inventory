<?php
// Allow CORS for React frontend (localhost:3000)
header("Access-Control-Allow-Origin: http://localhost:3000");

// Allow common HTTP methods used by your API
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow headers required by JWT and JSON communication
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Allow credentials if needed (for cookies, tokens, etc.)
header("Access-Control-Allow-Credentials: true");

// Handle preflight (OPTIONS) requests automatically
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>
