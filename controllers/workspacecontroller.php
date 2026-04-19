<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createworkspace(){
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
try{
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    $user_id=$decoded->user_id;
    $workspace_name= $data->workspace_name;
    $insertquery = "INSERT INTO workspace (Workspace_Name, Created_by) VALUES (?, ?)";
    $stmt = $conn-> prepare($insertquery);
     $stmt->bind_param("si", $workspace_name, $user_id);
     $stmt->execute();
      if($stmt->affected_rows > 0){
        http_response_code(201);
        echo json_encode(['message'=> 'User registered']);
         $conn->insert_id;
       $workspace_id =  $conn->insert_id;
$insertquery = "INSERT INTO workspace_members (`Workspace ID`, `User ID`, Role) VALUES (?, ?, ?)";

       $stmt = $conn-> prepare($insertquery);
    $role ='owner';
     $stmt->bind_param("iis", $workspace_id, $user_id, $role);
     $stmt->execute();
      }
      if($stmt->affected_rows > 0){

        http_response_code(201);
        echo json_encode(['message'=> 'User registered']);
         $conn->insert_id;
      }
      else{
      http_response_code(500);
      echo json_encode(['message'=> 'something went wrong!!']);
      return;
      }
     
       



  } catch(Exception $e){
    http_response_code(401);
 echo json_encode(['message'=>'invalid or expired token code']);
    }
}
function  getuserworkspaces(){
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

try{
    $token = str_replace('Bearer ', '', $header);
$secretkey = "focusdesk_super_secret_key_2026_xyz";
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    $user_id=$decoded->user_id;
$query = "SELECT w.ID, w.Workspace_Name FROM workspace w JOIN workspace_members wm ON w.ID = wm.`Workspace ID` WHERE wm.`User ID` = ?";
$stmt = $conn->prepare($query);
if(!$stmt) { echo json_encode(['error' => $conn->error]); return; }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$workspaces = $result->fetch_all(MYSQLI_ASSOC);
http_response_code(200);
echo json_encode($workspaces);
} catch(Exception $e){
    http_response_code(401);
echo json_encode(['message'=> $e->getMessage()]);;
}
}
function invitemember(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] 
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] 
    ?? apache_request_headers()['Authorization'] 
    ?? null;
    if(empty($header)){
        http_response_code(401);
        echo json_encode(['message'=> 'No token provided']);
        return;
    }
    try{
        $token = str_replace('Bearer ', '', $header);
        $secretkey = "focusdesk_super_secret_key_2026_xyz";
        $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
        $user_id = $decoded->user_id;
        $workspace_id = $data->workspace_id;
        $role = 'member';
        $insertquery = "INSERT INTO workspace_members (`Workspace ID`, `User ID`, Role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertquery);
        $stmt->bind_param("iis", $workspace_id, $user_id, $role);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(['message' => 'User added to workspace']);
    }catch(Exception $e){
        http_response_code(401);
        echo json_encode(['message'=>'invalid or expired token code']);
    }
}






    
?> 




