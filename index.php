<?php
session_start();

header('Location: welcome.php');
exit;
include 'db.php';

if(isset($_POST['ac'])) {
    $book_id = $_POST['ac'];
    $quantity = $_POST['quantity'];
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if(isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id] += $quantity;
    } else {
        $_SESSION['cart'][$book_id] = $quantity;
    }
    
    $message = "Book added to cart!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Inkspired Book Shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="modern-bg">

<header class="main-header">
    <div class="logo"><a href="index.php" style="color: white; text-decoration: none;">Inkspired Book Shop</a></div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="contact.php">Contact</a>
    </nav>
    <div class="user-actions">
        <?php if(isset($_SESSION['id'])): ?>
            <a href="logout.php" class="btn-login">Logout</a>
        <?php else: ?>
            <a href="signin.php" class="btn-login">Sign In</a>
        <?php endif; ?>
        <a href="cart.php" class="cart-link"><i class="fa fa-shopping-cart cart-icon"></i> Cart <?php echo isset($_SESSION['cart']) ? '(' . count($_SESSION['cart']) . ')' : ''; ?></a>
    </div>
</header>
<section class="hero">
    <div class="hero-content">
        <p class="promo-text">Up to 30% Off</p>
        <h1>Get Your New Book With The Best Price</h1>
        <a href="#featured" class="shop-now">Shop Now →</a>
    </div>
    <div class="hero-image">
        <img src="image/hero_girl.png" alt="Student holding books">
    </div> 
<div class="features-bar">
    <div class="feature-item">1 Secure Payment</div>
    <div class="feature-item">2Return Policy</div>
    <div class="feature-item">3 Free Shipping</div>
    <div class="feature-item">4 Customer Support</div>
</div>

<section id="featured" class="featured-section">
    <h2>Featured Books</h2>
    <?php if(isset($message)) echo "<p>$message</p>"; ?>
    <div class="book-grid">
        <?php
        $books = $conn->query("SELECT * FROM book");
        while($row = $books->fetch_assoc()): ?>
            <div class="book-card">
                <div class="badge">-30%</div>
                <img src="<?php echo htmlspecialchars($row['Image']); ?>" alt="<?php echo htmlspecialchars($row['BookTitle']); ?>">
                <h3><?php echo htmlspecialchars($row['BookTitle']); ?></h3>
                <div class="stars">★★★★★</div>
                <div class="price">RM<?php echo number_format($row['Price'], 2); ?></div>
                <form method="post" class="add-to-cart-form">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <label for="qty_<?php echo $row['BookID']; ?>" style="margin: 0;">Qty:</label>
                        <input type="number" id="qty_<?php echo $row['BookID']; ?>" name="quantity" value="1" min="1" max="100" style="width: 60px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <input type="hidden" value="<?php echo $row['BookID']; ?>" name="ac"/>
                    <button type="submit" class="add-to-cart-btn">Add To Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="top-categories">
    <h2>Top Categories</h2>
    <div class="categories-grid">
        <div class="category-item">
            <div class="category-icon"></div>
            <h3>Fiction</h3>
            <p>120 books</p>
        </div>
        <div class="category-item">
            <div class="category-icon">lawrence book</div>
            <h3>Non-Fiction</h3>
            <p>85 books</p>
        </div>
        <div class="category-item">
            <div class="category-icon">makiling books</div>
            <h3>Education</h3>
            <p>95 books</p>
        </div>
        <div class="category-item">
            <div class="category-icon">gozon books</div>
            <h3>Best Sellers</h3>
            <p>60 books</p>
        </div>
        <div class="category-item">
            <div class="category-icon">justine books</div>
            <h3>Mystery</h3>
            <p>75 books</p>
        </div>
        <div class="category-item">
            <div class="category-icon">letada na hindi nag seseen books
            </div>
            <h3>Romance</h3>
            <p>90 books</p>
        </div>
    </div>
</section>

</body>
</html>