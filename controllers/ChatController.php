<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function sendMessage(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){
         http_response_code(401); 
          echo json_encode(['message' => 'No token provided']);
           return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    try{
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    } catch (Exception $e) {
         http_response_code(401);
    echo json_encode(['message' => 'Invalid or expired token']);
    return;
    }
    $user_id = $decoded->user_id;
    $message = $data->newmessage;
    $workspace_id = $data->workspace_id;
    $stmt = $conn->prepare("INSERT INTO Messages (sent_by, message, workspace_id) VALUES (:user_id, :message, :workspace_id)");
    $stmt->execute([':user_id' => $user_id, ':message' => $message, ':workspace_id' => $workspace_id]);
    if($stmt->rowCount() > 0){ http_response_code(201); 
    echo json_encode(['message' => 'Message sent']); 
       return;
    }
        
    $mstmt = $conn->prepare("SELECT `User ID` FROM workspace_members WHERE `Workspace ID` = :workspace_id AND `User ID` != :user_id");
    $mstmt->execute([':workspace_id' => $workspace_id, ':user_id' => $user_id]);
    $members = $mstmt->fetchAll(PDO::FETCH_ASSOC);
    $notifmessage = "New message in your workspace";
    foreach($members as $row){
        $member_id = $row['User ID'];
        $nstmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (:member_id, :message, 0)");
        $nstmt->execute([':member_id' => $member_id, ':message' => $notifmessage]);
    }
}

function getMessage(){
    global $conn;
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    try{
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    } catch (Exception $e){
            http_response_code(401);
    echo json_encode(['message' => 'Invalid or expired token']);
    return;

    }
    parse_str($_SERVER['QUERY_STRING'] ?? '', $params);
    $workspace_id = $params['workspace_id'] ?? null;
    $stmt = $conn->prepare("SELECT Messages.*, Users.name AS sender_name FROM Messages JOIN Users ON Messages.sent_by = Users.id WHERE Messages.workspace_id = :workspace_id");
    $stmt->execute([':workspace_id' => $workspace_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($messages);
}

function deleteMessage(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? apache_request_headers()['Authorization'] ?? null;
    if(empty($header)){ http_response_code(401); echo json_encode(['message' => 'No token provided']); return; }
    $token = str_replace('Bearer ', '', $header);
    $secretkey = "focusdesk_super_secret_key_2026_xyz";
    try{
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid or expired token']);
    return;
}
    $message_id = $data->message_id;
    $stmt = $conn->prepare("DELETE FROM Messages WHERE ID = :message_id");
    $stmt->execute([':message_id' => $message_id]);
    http_response_code(200);
    echo json_encode(['message' => 'Message deleted']);
}
?>
