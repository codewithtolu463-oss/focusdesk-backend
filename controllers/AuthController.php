<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function register(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    if(empty($data->name) || empty($data->email) || empty($data->password)){
        http_response_code(400);
        echo json_encode(['message' => 'All fields are required']);
        return;
    }
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $data->email]);
    if($stmt->rowCount() > 0){
        http_response_code(409);
        echo json_encode(['message' => 'Email already exists']);
        return;
    }
    $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute([':name' => $data->name, ':email' => $data->email, ':password' => $hashedPassword]);
    if($stmt->rowCount() > 0){
        http_response_code(201);
        echo json_encode(['message' => 'User registered']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Something went wrong']);
    }
}

function login(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    if(empty($data->email) || empty($data->password)){
        http_response_code(400);
        echo json_encode(['message' => 'All fields are required']);
        return;
    }
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $data->email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($data->password, $user['password'])){
        http_response_code(200);
        $secretkey = "focusdesk_super_secret_key_2026_xyz";
        $payload = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'exp' => time() + 3600
        ];
        $token = JWT::encode($payload, $secretkey, 'HS256');
        echo json_encode(['message' => 'User logged in', 'token' => $token]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid credentials, try again.']);
    }
}
?>
