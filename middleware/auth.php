<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key; 
require_once '../config/db.php';
require_once '../vendor/autoload.php';

function Authenticate(){
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? null ;
if(empty($header)){
    http_response_code(401);
    echo json_encode(['message'=> ' No token provided']);
    return;
}
$token = str_replace('Bearer ', '', $header);

try{
    $secretkey = "your_secret_key_here";
    $decoded = JWT::decode($token, new Key($secretkey, 'HS256'));
    return $decoded;
} catch(Exception $e){
    http_response_code(401);
 echo json_encode(['message'=>'invalid or expired token code']);
    }
    


}

?> 