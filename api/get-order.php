<?php
header('Content-Type: application/json');
include '../config.php';

$id = intval($_GET['id']);

$query = "SELECT o.id, o.total, o.status, o.created_at, u.email as customer_email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id=$id";
$result = $conn->query($query);
$order = $result->fetch_assoc();

$query = "SELECT oi.quantity, oi.price, b.title FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id=$id";
$result = $conn->query($query);

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode(['order' => $order, 'items' => $items]);
?>