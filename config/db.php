<?php
error_log("URI: " . $_SERVER['REQUEST_URI'] . " METHOD: " . $_SERVER['REQUEST_METHOD']);
$host = getenv('bcaraw5zsuiq512xifvc-mysql.services.clever-cloud.com');
$username = getenv('uc7besw319b7eviy');
$password = getenv('VGw583xGjoVq5x8Z2J6V');
$database_name = getenv('bcaraw5zsuiq512xifvc');
$port = getenv('3306');
$conn = new mysqli($host, $username, $password, $database_name, $port);
if($conn->connect_error){
    die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
}
?>
