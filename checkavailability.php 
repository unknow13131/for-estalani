<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "bookstore");
if ($conn->connect_error) {
    echo json_encode(['available' => false, 'message' => 'Database connection error']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['field']) && isset($_POST['value'])) {
    $field = $_POST['field'];
    $value = mysqli_real_escape_string($conn, $_POST['value']);
    
    if ($field === 'uname') {
        $query = $conn->query("SELECT id FROM users WHERE username = '$value'");
        if ($query->num_rows > 0) {
            echo json_encode(['available' => false, 'message' => 'Username already exists. Please choose a different one.']);
        } else {
            echo json_encode(['available' => true, 'message' => 'Username available']);
        }
    } 
    elseif ($field === 'email') {
        $query = $conn->query("SELECT CustomerEmail FROM customer WHERE CustomerEmail = '$value'");
        if ($query->num_rows > 0) {
            echo json_encode(['available' => false, 'message' => 'Email already registered. Please use a different email or login.']);
        } else {
            echo json_encode(['available' => true, 'message' => 'Email available']);
        }
    } 
    elseif ($field === 'contact') {
        $query = $conn->query("SELECT CustomerPhone FROM customer WHERE CustomerPhone = '$value'");
        if ($query->num_rows > 0) {
            echo json_encode(['available' => false, 'message' => 'Phone number already registered. Please use a different number.']);
        } else {
            echo json_encode(['available' => true, 'message' => 'Phone available']);
        }
    }
    else {
        echo json_encode(['available' => false, 'message' => 'Invalid field']);
    }
} 
else {
    echo json_encode(['available' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
