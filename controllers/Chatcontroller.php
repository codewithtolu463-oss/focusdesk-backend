<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

 function sendMessage(){

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
   $message = $data->newmessage;
    $workspace_id= $data->workspace_id;
 $insertquery = "INSERT INTO  Messages (sent_by, message, workspace_id ) VALUES(?, ?, ?)";
$stmt = $conn->prepare($insertquery);
$stmt->bind_param("isi",  $user_id, $message, $workspace_id);
$stmt->execute(); 
if($stmt->affected_rows > 0){
        http_response_code(201);
        echo json_encode(['message'=> 'Task Created']);
}
 
$memberquery = "SELECT `User ID` FROM workspace_members WHERE `Workspace ID` = ? AND `User ID` != ?";
$mstmt = $conn->prepare($memberquery);
$mstmt->bind_param("ii", $workspace_id, $user_id);
$mstmt->execute();
$mresult = $mstmt->get_result();
$notifmessage = "New message in your workspace";
while($row = $mresult->fetch_assoc()){
    $member_id = $row['User ID'];
    $nstmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)");
    $nstmt->bind_param("is", $member_id, $notifmessage);
    $nstmt->execute();
}


 }
function getMessage(){

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
$selectquery = "SELECT Messages.*, Users.name AS sender_name FROM Messages JOIN Users ON Messages.sent_by = Users.id WHERE Messages.workspace_id = ?";
$stmt = $conn->prepare($selectquery);
$stmt->bind_param("i", $workspace_id );
$stmt->execute(); 
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
        http_response_code(200);
        echo json_encode($tasks);


 }
 function deleteMessage(){
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
    $message_id = $data->message_id;
   
$deletequery = 'DELETE FROM  Messages WHERE ID =?';
 $stmt = $conn->prepare($deletequery);
$stmt->bind_param("i", $message_id );
$stmt->execute();
        http_response_code(200);
        echo json_encode(['message'=> 'Task deleted ']);
  

 }


?>