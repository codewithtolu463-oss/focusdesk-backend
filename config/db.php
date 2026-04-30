<?php
$host = getenv('mysql.railway.internal');
$username = getenv('root');
$password = getenv('fcoOgbHYRYOjdOjlnPkPjULLIwVAUsYu');
$database_name = getenv('railway');
$port = getenv('3306');
$conn = new mysqli($host, $username, $password, $database_name, $port);
if($conn->connect_error){die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));}
?>
