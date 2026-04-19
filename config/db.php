<?php
$host = "sql303.infinityfree.com";
$username = "if0_41699219";
$password = "toluyonda33";
$database_name = "if0_41699219_focus_desk";
$conn = new mysqli($host, $username, $password, $database_name);
if($conn->connect_error){die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));}
?>