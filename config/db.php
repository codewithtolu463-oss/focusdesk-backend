<?php
$host = getenv('mysql.railway.internal');
$username = getenv('root');
$password = getenv('fcoOgbHYRYOjdOjlnPkPjULLIwVAUsYu');
$database_name = getenv('railway');
$port = getenv('3306');
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$database_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(['message' => 'Connection failed: ' . $e->getMessage()]));
}
?>
