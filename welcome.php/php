<?php
session_start();
include 'db.php';

// handle login POST
$login_error = '';
if(isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $pwd = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'user';

    $res = $conn->query("SELECT id, password FROM users WHERE username = '$username'");
    if($res && $res->num_rows == 1) {
        $row = $res->fetch_assoc();
        if(password_verify($pwd, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['role'] = $role;
            if($role === 'admin') {
                header('Location: admin.php'); exit;
            } else {
                header('Location: shop.php'); exit;
            }
        }
    }
    $login_error = 'Invalid username or password.';
}

// handle register POST
$reg_error = '';
if(isset($_POST['register'])) {
    $first = $conn->real_escape_string($_POST['first_name']);
    $last = $conn->real_escape_string($_POST['last_name']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $pwd = $_POST['password'];
    $pwd2 = $_POST['confirm_password'];

    if(strlen($pwd) < 7) {
        $reg_error = 'Password must be at least 7 characters.';
    } elseif($pwd !== $pwd2) {
        $reg_error = 'Passwords do not match.';
    } else {
        // check existing username
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        if($check && $check->num_rows > 0) {
            $reg_error = 'Username already taken.';
        } else {
            // create user
            $hashed = password_hash($pwd, PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$hashed')");
            $uid = $conn->insert_id;
            $name = $first . ' ' . $last;
            // note: make sure customer table has `CustomerDOB` column if you want to store dob
            $conn->query("INSERT INTO customer (CustomerName, CustomerPhone, CustomerEmail, UserID, CustomerDOB) VALUES ('$name', '', '$email', $uid, '$dob')");
            // auto login and redirect
            $_SESSION['id'] = $uid;
            header('Location: shop.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Inkspired Book Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="landing-page">
    <div class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-container">
            <h1 class="main-heading">Welcome to Inkspired Book Shop</h1>
            <p class="sub-heading">Discover your next great read...</p>
            <div class="cta-buttons">
                <button class="button button-signin" id="signInBtn">Sign In</button>
                <button class="button button-getstarted" id="getStartedBtn">Get Started</button>
            </div>
            <blockquote class="quote">
                "A room without books is like a body without a soul" <br>
                <cite>— Marcus Tullius Cicero</cite>
            </blockquote>
        </div>
    </div>

    <!-- Auth Modal (Login/Register Tabs) -->
    <div id="authModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeAuth">&times;</span>
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p class="tagline">Access your account or create a new one</p>
            </div>
            <div class="auth-tabs">
                <button class="tab login-tab active">Login</button>
                <button class="tab register-tab">Register</button>
            </div>
            <form id="loginForm" method="post" action="welcome.php" class="auth-form">
                <?php if($login_error): ?>
                    <p class="error-message" style="display:block; text-align:center; color: #dc3545;"><?php echo $login_error; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" id="loginUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="loginRole">Role</label>
                    <select id="loginRole" name="role" required>
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="login" class="button button-primary full-width">Sign In</button>
            </form>
            <form id="registerForm" method="post" action="welcome.php" class="auth-form" style="display:none;">
                <?php if($reg_error): ?>
                    <p class="error-message" style="display:block; text-align:center; color: #dc3545;"><?php echo $reg_error; ?></p>
                <?php endif; ?>
                <div class="row">
                    <div class="form-group half">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="first_name" required>
                    </div>
                    <div class="form-group half">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="last_name" required>
                    </div>
                </div>
                <!-- optional role selector on registration, defaults to user -->
                <div class="form-group" style="display:none;">
                    <label for="regRole">Role</label>
                    <select id="regRole" name="role">
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label for="regEmail">Email</label>
                    <input type="email" id="regEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="regUsername">Username</label>
                    <input type="text" id="regUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <input type="password" id="regPassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirm_password" required>
                </div>
                <button type="submit" name="register" class="button button-primary full-width">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        // elements
        var authModal = document.getElementById('authModal');
        var signInBtn = document.getElementById('signInBtn');
        var getStartedBtn = document.getElementById('getStartedBtn');
        var closeAuth = document.getElementById('closeAuth');

        var loginTab = document.querySelector('.login-tab');
        var registerTab = document.querySelector('.register-tab');
        var loginForm = document.getElementById('loginForm');
        var registerForm = document.getElementById('registerForm');

        function showLogin() {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        }
        function showRegister() {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.style.display = 'block';
            loginForm.style.display = 'none';
        }

        // open modal handlers
        signInBtn.onclick = function() {
            authModal.style.display = 'block';
            showLogin();
        };
        getStartedBtn.onclick = function() {
            authModal.style.display = 'block';
            showRegister();
        };

        // tab clicks
        loginTab.addEventListener('click', showLogin);
        registerTab.addEventListener('click', showRegister);

        // close
        closeAuth.onclick = function() {
            authModal.style.display = 'none';
        };

        // outside click
        window.onclick = function(event) {
            if (event.target == authModal) {
                authModal.style.display = 'none';
            }
        };
    </script>
</body>
</html>