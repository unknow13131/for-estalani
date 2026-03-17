<?php
header('Content-Type: application/json');
include '../config.php';

$id = intval($_GET['id']);

$query = "SELECT id, email, role FROM users WHERE id=$id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

echo json_encode($user);
?>
