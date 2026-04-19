<?php


require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function getnotifcation(){

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
   
 $selectquery = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = $conn->prepare($selectquery);
$stmt->bind_param("i",  $user_id);
  $stmt->execute(); 
$result = $stmt->get_result();
$notications = $result->fetch_all(MYSQLI_ASSOC);
        http_response_code(200);
        echo json_encode($notications);

}
 function markread(){
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
   $notification_id = $data->notification_id;
$updatequery = 'UPDATE notifications SET is_read = 1 WHERE ID =?';
 $stmt = $conn->prepare($updatequery);
$stmt->bind_param("i", $notification_id );
$stmt->execute();
        http_response_code(200);
        echo json_encode(['message'=> ' Notification sent ']);
  
 }



?>





