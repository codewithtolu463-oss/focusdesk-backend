<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 function createTask(){
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
    $workspace_id= $data->workspace_id;
    $title= $data->title;
    $description= $data->description;
     $status = $data->status;
    $position = $data->position;

$insertquery = "INSERT INTO  tasks (workspace_id, title, description, status , position, created_by ) VALUES(?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertquery);
$stmt->bind_param("isssii", $workspace_id, $title, $description, $status, $position, $user_id );
$stmt->execute(); 
if($stmt->affected_rows > 0){
        http_response_code(201);
        echo json_encode(['message'=> 'Task Created']);
}
 }
  function getTask(){
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
$selectquery = "SELECT * FROM   tasks WHERE workspace_id =? ";
$stmt = $conn->prepare($selectquery);
$stmt->bind_param("i", $workspace_id );
$stmt->execute(); 
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
        http_response_code(200);
        echo json_encode($tasks);
  }
   function updateTask(){
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
    $task_id = $data->task_id;
     $status = $data->status;
      $position = $data->position;
$updatequery = 'UPDATE tasks SET status = ?, position = ? WHERE ID =?';
 $stmt = $conn->prepare($updatequery);
$stmt->bind_param("sii", $status, $position, $task_id );
$stmt->execute();
        http_response_code(200);
        echo json_encode(['message'=> 'Task Updated ']);
  
   }
   function deleteTask(){
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
    $task_id = $data->task_id;
$deletequery = 'DELETE FROM tasks WHERE ID =?';
 $stmt = $conn->prepare($deletequery);
$stmt->bind_param("i", $task_id );
$stmt->execute();
        http_response_code(200);
        echo json_encode(['message'=> 'Task deleted ']);
  




   }