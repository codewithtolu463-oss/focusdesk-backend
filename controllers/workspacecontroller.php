<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createworkspace(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    try {
        $secretkey = "focusdesk_super_secret_key_2026_xyz";
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
        $user_id = $decoded->user_id;
        $workspace_name = $data->workspace_name;
        $stmt = $conn->prepare("INSERT INTO workspace (Workspace_Name, Created_by) VALUES (?, ?)");
        $stmt->execute([$workspace_name, $user_id]);
        if($stmt->rowCount() > 0){
            $workspace_id = $conn->lastInsertId();
            $role = 'owner';
            $stmt2 = $conn->prepare("INSERT INTO workspace_members (`Workspace ID`, `User ID`, Role) VALUES (?, ?, ?)");
            $stmt2->execute([$workspace_id, $user_id, $role]);
            http_response_code(201);
            echo json_encode(['message' => 'Workspace created']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Something went wrong']);
        }
    } catch(Exception $e){
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
    }
}

function getuserworkspaces(){
    global $conn;
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    try {
        $token = str_replace('Bearer ', '', $header);
        $secretkey = "focusdesk_super_secret_key_2026_xyz";
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
        $user_id = $decoded->user_id;
        $stmt = $conn->prepare("SELECT w.ID, w.Workspace_Name FROM workspace w JOIN workspace_members wm ON w.ID = wm.`Workspace ID` WHERE wm.`User ID` = ?");
        $stmt->execute([$user_id]);
        $workspaces = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($workspaces);
    } catch(Exception $e){
        http_response_code(401);
        echo json_encode(['message' => $e->getMessage()]);
    }
}

function invitemember(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    try {
        $token = str_replace('Bearer ', '', $header);
        $secretkey = "focusdesk_super_secret_key_2026_xyz";
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
        $user_id = $decoded->user_id;
        $workspace_id = $data->workspace_id;
        $role = 'member';
        $stmt = $conn->prepare("INSERT INTO workspace_members (`Workspace ID`, `User ID`, Role) VALUES (?, ?, ?)");
        $stmt->execute([$workspace_id, $user_id, $role]);
        http_response_code(201);
        echo json_encode(['message' => 'User added to workspace']);
    } catch(Exception $e){
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
    }
}
?>