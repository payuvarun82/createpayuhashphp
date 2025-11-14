<?php
// ==============================
// createPayuHashKey.php
// ==============================

// Allow CORS (for mobile apps / web calls)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Your secure SALT value (KEEP SECRET)
$SALT = "izF09TlpX4ZOwmf9MvXijwYsBPUmxYHD";  // replace this with your actual PayU salt

// Read incoming raw POST body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validate incoming request
if (!isset($data['query'])) {
    http_response_code(400);
    echo json_encode([
        "error" => true,
        "message" => "Missing 'query' field in request body"
    ]);
    exit;
}

// Extract hashname from GraphQL mutation string
// Example query:
// mutation { createPayuHashKey ( hashname: "RqhS3D|validateVPA|7879357664@ybl|") { get_payment_hash }}
preg_match('/hashname:\s*\\"([^"]+)\\"/', $data['query'], $matches);

if (!isset($matches[1])) {
    http_response_code(400);
    echo json_encode([
        "error" => true,
        "message" => "Could not extract hashname from query"
    ]);
    exit;
}

$hashString = $matches[1];

// Append salt to the hash string
$hashStringWithSalt = $hashString . $SALT;

// Generate SHA512 hash
$hashValue = hash("sha512", $hashStringWithSalt);

// Build GraphQL-style response
$response = [
    "data" => [
        "createPayuHashKey" => [
            "get_payment_hash" => $hashValue
        ]
    ]
];

// Return JSON response
echo json_encode($response);
?>
