<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createTask(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    try {
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
        return;
    }
    $user_id = $decoded->user_id;
    $workspace_id = $data->workspace_id;
    $title = $data->title;
    $description = $data->description;
    $status = $data->status;
    $position = $data->position;
    $stmt = $conn->prepare("INSERT INTO tasks (workspace_id, title, description, status, position, created_by) VALUES(?, ?, ?, ?, ?, ?)");
    $stmt->execute([$workspace_id, $title, $description, $status, $position, $user_id]);
    if($stmt->rowCount() > 0){
        http_response_code(201);
        echo json_encode(['message' => 'Task Created']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Something went wrong']);
    }
}

function getTask(){
    global $conn;
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    try {
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
        return;
    }
    parse_str($_SERVER['QUERY_STRING'] ?? '', $params);
    $workspace_id = $params['workspace_id'] ?? null;
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE workspace_id = ?");
    $stmt->execute([$workspace_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($tasks);
}

function updateTask(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    try {
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
        return;
    }
    $task_id = $data->task_id;
    $status = $data->status;
    $position = $data->position;
    $stmt = $conn->prepare("UPDATE tasks SET status = ?, position = ? WHERE ID = ?");
    $stmt->execute([$status, $position, $task_id]);
    http_response_code(200);
    echo json_encode(['message' => 'Task Updated']);
}

function deleteTask(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    try {
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
        return;
    }
    $task_id = $data->task_id;
    $stmt = $conn->prepare("DELETE FROM tasks WHERE ID = ?");
    $stmt->execute([$task_id]);
    http_response_code(200);
    echo json_encode(['message' => 'Task Deleted']);
}
?>