<?php
error_log("URI: " . $_SERVER['REQUEST_URI'] . " METHOD: " . $_SERVER['REQUEST_METHOD']);
$host = getenv('MYSQL_ADDON_HOST');
$username = getenv('MYSQL_ADDON_USER');
$password = getenv('MYSQL_ADDON_PASSWORD');
$database_name = getenv('MYSQL_ADDON_DB');
$port = getenv('MYSQL_ADDON_PORT');
$conn = new mysqli($host, $username, $password, $database_name, $port);
if($conn->connect_error){
    die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
}
?>
