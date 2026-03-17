<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bookstore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['pwd'];
$role = isset($_POST['role']) ? $_POST['role'] : 'user';

// if your users table has a `role` column you can enforce it here;
// otherwise we simply remember the choice in the session after login
$sql = "SELECT * FROM users WHERE username='$user'";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    if (password_verify($pass, $row['password'])) {
        $_SESSION['id'] = $row['id']; 
        $_SESSION['role'] = $role; // remember which role the user selected
        // simple redirect based on chosen role (could verify against DB if available)
        if ($role === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        header("Location: login.php?errcode=1");
        exit();
    }
} else {
    header("Location: login.php?errcode=1");
    exit();
}
?>