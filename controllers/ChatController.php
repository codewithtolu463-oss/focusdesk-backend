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

 $stmt = $conn->prepare("INSERT INTO messages (sent_by, message, workspace_id) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $user_id, $message, $workspace_id);
$stmt->execute();
if($stmt->affected_rows > 0){
    $mstmt = $conn->prepare("SELECT `User ID` FROM workspace_members WHERE `Workspace ID` = ? AND `User ID` != ?");
$mstmt->bind_param("ii", $workspace_id, $user_id);
$mstmt->execute();
$members = $mstmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $notifmessage = "New message in your workspace";
    foreach($members as $row){
        $member_id = $row['User ID'];
        $nstmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)");
$nstmt->bind_param("is", $member_id, $notifmessage);
$nstmt->execute();
    }
    http_response_code(201);
    echo json_encode(['message'=>'Message sent']);
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
   $stmt = $conn->prepare("SELECT messages.*, Users.name AS sender_name FROM Messages JOIN Users ON Messages.sent_by = Users.ID WHERE Messages.workspace_id = ?");
$stmt->bind_param("i", $workspace_id);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
  $stmt = $conn->prepare("DELETE FROM messages WHERE ID = ?");
$stmt->bind_param("i", $message_id);
$stmt->execute();
    http_response_code(200);
    echo json_encode(['message' => 'Message deleted']);
}
?>
