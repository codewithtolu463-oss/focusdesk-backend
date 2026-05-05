<?php
ob_start();

header("Access-Control-Allow-Origin: https://focusdeskk.netlify.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
if ($_SERVER['REQUEST_URI'] === '/test') {
    echo json_encode(['message' => 'Server is working!']);
    exit();
}
if ($_SERVER['REQUEST_URI'] === '/dbtest') {
    require_once 'config/db.php';
    echo json_encode(['message' => 'DB connected!']);
    exit();
}

require_once 'routes/api.php';

?>
