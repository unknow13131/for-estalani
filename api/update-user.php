<?php
header('Content-Type: application/json');
include '../config.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id']);
$email = $conn->real_escape_string($data['email']);
$role = $conn->real_escape_string($data['role']);

$query = "UPDATE users SET email='$email', role='$role' WHERE id=$id";

if ($conn->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
?>
