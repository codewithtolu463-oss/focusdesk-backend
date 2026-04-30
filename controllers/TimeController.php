<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function startTimer(){
global $conn;
$data = json_decode(file_get_contents("php://input"));
       $header = $_SERVER['HTTP_AUTHORIZATION'] 
       ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] 
       ?? apache_request_headers()['Authorization'] 
       ?? null;
if(empty($header)){
    http_response_code(401);
    echo json_encode(['message'=> ' No token provided']);
    return;
}
$token = str_replace('Bearer ', '', $header);
$secretkey = "focusdesk_super_secret_key_2026_xyz";
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    $user_id = $decoded->user_id;
    $task_id= $data->task_id;
    $insertquery = "INSERT INTO time_logs (task_id, user_id, start_time) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($insertquery);
$stmt->bind_param("ii",  $task_id, $user_id);
$stmt->execute(); 
error_log("task_id: " . $task_id);
error_log("user_id: " . $user_id);
error_log("affected: " . $stmt->affected_rows);
error_log("stmt error: " . $stmt->error);
if($stmt->affected_rows > 0){
        http_response_code(201);
       echo json_encode(['message'=> 'Time started', 'log_id' => $conn->insert_id]);
}
 


}

function stopTimer(){
    global $conn;
$data = json_decode(file_get_contents("php://input"));
       $header = $_SERVER['HTTP_AUTHORIZATION'] 
       ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] 
       ?? apache_request_headers()['Authorization'] 
       ?? null;
if(empty($header)){
    http_response_code(401);
    echo json_encode(['message'=> ' No token provided']);
    return;
}
$token = str_replace('Bearer ', '', $header);
$secretkey = "focusdesk_super_secret_key_2026_xyz";
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    $user_id = $decoded->user_id;
    $log_id= $data->log_id;
$updatequery = "UPDATE time_logs SET end_time = NOW()  WHERE id =?";
$stmt = $conn->prepare($updatequery);
$stmt->bind_param("i",  $log_id);
$stmt->execute(); 
if($stmt->affected_rows > 0){
        http_response_code(201);
        echo json_encode(['message'=> 'Time stopped']);
}
}

function getlogs(){
global $conn;
    $data = json_decode(file_get_contents("php://input"));
       $header = $_SERVER['HTTP_AUTHORIZATION'] 
       ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] 
       ?? apache_request_headers()['Authorization'] 
       ?? null;
if(empty($header)){
    http_response_code(401);
    echo json_encode(['message'=> ' No token provided']);
    return;
}

$token = str_replace('Bearer ', '', $header);
$secretkey = "focusdesk_super_secret_key_2026_xyz";
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    $user_id=$decoded->user_id;
    parse_str($_SERVER['QUERY_STRING'] ?? '', $params);
$workspace_id = $params['workspace_id'] ?? null;
$yourquery = "SELECT time_logs.*, tasks.title FROM time_logs JOIN tasks ON time_logs.task_id = tasks.ID WHERE tasks.workspace_id =? AND time_logs.user_id = ?";
$stmt = $conn->prepare($yourquery);
$stmt->bind_param("ii", $workspace_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$logs = $result->fetch_all(MYSQLI_ASSOC);
http_response_code(200);
echo json_encode($logs);
}

?>
