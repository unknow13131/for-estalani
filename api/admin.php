<?php 
session_start(); 

if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header('Location: login.php?errcode=2'); 
    exit; 
} 

include 'config.php'; 

// Books count
$query = "SELECT COUNT(*) as total FROM books";
$result = $conn->query($query);
$books_count = $result->fetch_assoc()['total'];

// Users count
$query = "SELECT COUNT(*) as total FROM users";
$result = $conn->query($query);
$users_count = $result->fetch_assoc()['total'];

// Orders count
$query = "SELECT COUNT(*) as total FROM orders";
$result = $conn->query($query);
$orders_count = $result->fetch_assoc()['total'];

// Revenue
$query = "SELECT SUM(total) as revenue FROM orders 
          WHERE status IN ('processing', 'shipped', 'delivered')";
$result = $conn->query($query);
$revenue = $result->fetch_assoc()['revenue'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Inkspired Book Shop</title>
    <link rel="stylesheet" href="admin-style.css">
</head>

<body>
<div class="admin-container">

    <aside class="sidebar">
        <div class="logo">Inkspired</div>

        <nav class="sidebar-nav">
            <a href="#dashboard" class="nav-item active" onclick="showSection('dashboard')">
                📊 Dashboard
            </a>

            <a href="#books" class="nav-item" onclick="showSection('books')">
                📚 Manage Books
            </a>

            <a href="#orders" class="nav-item" onclick="showSection('orders')">
                📦 View Orders
            </a>

            <a href="#users" class="nav-item" onclick="showSection('users')">
                👥 Manage Users
            </a>

            <a href="#store" class="nav-item" onclick="showSection('store')">
                🏪 View Store
            </a>

            <hr style="margin: 20px 0; opacity: 0.3;">

            <a href="logout.php" class="nav-item logout">
                🚪 Logout
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <h1>Admin Dashboard</h1>
            <div class="user-info">
                <span>Welcome, Admin</span>
            </div>
        </header>

        <div class="content-area">

            <!-- Dashboard -->
            <section id="dashboard" class="section active">
                <h2>Dashboard Overview</h2>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon books">📚</div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $books_count; ?></div>
                            <div class="stat-label">Books</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon users">👥</div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $users_count; ?></div>
                            <div class="stat-label">Users</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orders">📦</div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $orders_count; ?></div>
                            <div class="stat-label">Orders</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon revenue">💰</div>
                        <div class="stat-info">
                            <div class="stat-value">
                                $<?php echo number_format($revenue, 2); ?>
                            </div>
                            <div class="stat-label">Revenue</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Books -->
            <section id="books" class="section">
                <div class="section-header">
                    <h2>Manage Books</h2>
                    <button class="btn btn-primary" onclick="showAddBookForm()">+ Add Book</button>
                </div>
                <div id="books-content"></div>
            </section>

            <!-- Orders -->
            <section id="orders" class="section">
                <div class="section-header">
                    <h2>View Orders</h2>
                </div>
                <div id="orders-content"></div>
            </section>

            <!-- Users -->
            <section id="users" class="section">
                <div class="section-header">
                    <h2>Manage Users</h2>
                </div>
                <div id="users-content"></div>
            </section>

            <!-- Store -->
            <section id="store" class="section">
                <div class="section-header">
                    <h2>Store Analytics</h2>
                </div>
                <div id="store-content"></div>
            </section>

        </div>
    </main>

</div>

<script src="admin-script.js"></script>
</body>
</html>
