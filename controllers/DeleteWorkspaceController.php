<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function deleteWorkspace(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    $workspace_id = $data->workspace_id;
    $stmt = $conn->prepare("DELETE FROM workspace WHERE ID = :workspace_id");
    $stmt->execute([':workspace_id' => $workspace_id]);
    http_response_code(200);
    echo json_encode(['message' => 'Workspace deleted']);
}
?>