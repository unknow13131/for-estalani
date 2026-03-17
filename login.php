<?php
session_start();
include 'db.php';

$login_error = '';

if(isset($_POST['login'])) {
    $uname = $conn->real_escape_string($_POST['username']);
    $pwd = $_POST['pwd'];

    $res = $conn->query("SELECT * FROM users WHERE username = '$uname'");
    if($res && $res->num_rows == 1) {
        $row = $res->fetch_assoc();
        if(password_verify($pwd, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            header('Location: shop.php');
            exit;
        }
    }
    $login_error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - Inkspired Book Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Login</h1>
        <?php if($login_error): ?>
            <p class="error-message"><?php echo $login_error; ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pwd" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>