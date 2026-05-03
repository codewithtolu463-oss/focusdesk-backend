<?php
error_log("URI: " . $_SERVER['REQUEST_URI'] . " METHOD: " . $_SERVER['REQUEST_METHOD']);
$host = getenv('MYSQLHOST');
$username = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$database_name = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');
$conn = new mysqli($host, $username, $password, $database_name, $port);
if($conn->connect_error){
    die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
}
?>
