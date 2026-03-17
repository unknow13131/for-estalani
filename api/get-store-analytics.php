<?php
header('Content-Type: application/json');
include '../config.php';

// Total books in inventory
$query = "SELECT COUNT(*) as total FROM books";
$result = $conn->query($query);
$total_books = $result->fetch_assoc()['total'];

// Total orders
$query = "SELECT COUNT(*) as total FROM orders";
$result = $conn->query($query);
$total_orders = $result->fetch_assoc()['total'];

// Total revenue
$query = "SELECT COALESCE(SUM(total), 0) as revenue FROM orders WHERE status IN ('processing', 'shipped', 'delivered')";
$result = $conn->query($query);
$total_revenue = $result->fetch_assoc()['revenue'];

// Total customers
$query = "SELECT COUNT(*) as total FROM users WHERE role='customer'";
$result = $conn->query($query);
$total_customers = $result->fetch_assoc()['total'];

// Top selling books
$query = "SELECT b.title, SUM(oi.quantity) as quantity_sold, SUM(oi.price * oi.quantity) as revenue FROM order_items oi JOIN books b ON oi.book_id = b.id GROUP BY b.id ORDER BY quantity_sold DESC LIMIT 5";
$result = $conn->query($query);
$top_books = [];
while ($row = $result->fetch_assoc()) {
    $top_books[] = $row;
}

// Recent orders
$query = "SELECT o.id, o.total, o.status, o.created_at, u.email as customer_email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10";
$result = $conn->query($query);
$recent_orders = [];
while ($row = $result->fetch_assoc()) {
    $recent_orders[] = $row;
}

echo json_encode([
    'total_books' => $total_books,
    'total_orders' => $total_orders,
    'total_revenue' => $total_revenue,
    'total_customers' => $total_customers,
    'top_books' => $top_books,
    'recent_orders' => $recent_orders
]);
?>
