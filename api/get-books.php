<?php
header('Content-Type: application/json');
include '../config.php';

$result = $conn->query("SELECT * FROM books");

$books = [];

while($row = $result->fetch_assoc()){
    $books[] = $row;
}

echo json_encode(['books' => $books]);
?>