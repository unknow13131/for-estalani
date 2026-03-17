<?php
header('Content-Type: application/json');
include '../config.php';

$result = $conn->query("SELECT id,email,role,created_at FROM users");

$users = [];

while($row = $result->fetch_assoc()){
    $users[] = $row;
}

echo json_encode(['users' => $users]);
?>