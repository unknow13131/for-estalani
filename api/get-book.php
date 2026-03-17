<?php
header('Content-Type: application/json');
include '../config.php';

$id = intval($_GET['id']);

$query = "SELECT * FROM books WHERE id=$id";
$result = $conn->query($query);
$book = $result->fetch_assoc();

echo json_encode($book);
?>
