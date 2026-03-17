<?php
session_start();
include 'db.php';

$reg_error = '';

if(isset($_POST['register'])) {
    $uname = $conn->real_escape_string($_POST['username']);
    $pwd = $_POST['password'];
    $pwd2 = $_POST['password2'];
    $dob = $conn->real_escape_string($_POST['dob']);

    // Password validation
    if(strlen($pwd) < 7) {
        $reg_error = 'Password must be at least 7 characters.';
    } elseif($pwd !== $pwd2) {
        $reg_error = 'Passwords do not match.';
    } else {
        // Check username
        $check = $conn->query("SELECT id FROM users WHERE username = '$uname'");
        if($check && $check->num_rows > 0) {
            $reg_error = 'Username already taken.';
        } else {
            // Auto-generate first & last name from username
            $first = ucfirst($uname);
            $last = 'User';
            $email = $uname . '@example.com';
            $hashed = password_hash($pwd, PASSWORD_DEFAULT);

            // Insert into users table
            $conn->query("INSERT INTO users (username, FirstName, LastName, email, password, account_type)
                         VALUES ('$uname', '$first', '$last', '$email', '$hashed', 'user')");
            $uid = $conn->insert_id;

            // Insert into customer table
            $conn->query("INSERT INTO customer (CustomerName, CustomerPhone, CustomerEmail, UserID, CustomerDOB)
                         VALUES ('$first $last', '', '$email', $uid, '$dob')");

            // Log in immediately
            $_SESSION['id'] = $uid;
            header('Location: shop.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - Inkspired Book Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Create Account</h1>
        <?php if($reg_error): ?>
            <p class="error-message"><?php echo $reg_error; ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password2" required>
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" required>
            </div>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>