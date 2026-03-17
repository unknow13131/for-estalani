<?php
header('Content-Type: application/json');
include '../config.php';

$data = json_decode(file_get_contents('php://input'), true);

$title = $conn->real_escape_string($data['title']);
$author = $conn->real_escape_string($data['author']);
$description = $conn->real_escape_string($data['description'] ?? '');
$price = floatval($data['price']);
$quantity = intval($data['quantity']);
$isbn = $conn->real_escape_string($data['isbn'] ?? '');
$category = $conn->real_escape_string($data['category'] ?? '');

$query = "INSERT INTO books (title, author, description, price, quantity, isbn, category) VALUES ('$title', '$author', '$description', $price, $quantity, '$isbn', '$category')";

if ($conn->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
?>
