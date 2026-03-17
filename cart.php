<?php
session_start();
include 'db.php'; // your database connection

// Remove item from cart
if(isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if(isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart.php");
    exit;
}

// Update quantities
if(isset($_POST['update_cart'])) {
    foreach($_POST['quantities'] as $book_id => $qty) {
        $book_id = intval($book_id);
        $qty = intval($qty);
        if($qty > 0) {
            $_SESSION['cart'][$book_id] = $qty; // store quantity only
        } else {
            unset($_SESSION['cart'][$book_id]);
        }
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - Inkspired Book Shop</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="shop-page">

<header class="main-header">
    <div class="logo"><a href="index.php" style="color:white;text-decoration:none;">Inkspired Book Shop</a></div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <?php if(isset($_SESSION['id'])): ?>
            <a href="logout.php" class="nav-logout">Logout</a>
        <?php else: ?>
            <a href="signin.php" class="nav-login">Login</a>
        <?php endif; ?>
    </nav>
</header>

<section class="featured-books">
    <h2>Your Shopping Cart</h2>

    <?php if(empty($_SESSION['cart'])): ?>
        <div style="text-align:center; padding:2rem; background:#f8d7da; color:#721c24; border-radius:8px;">
            Your cart is empty. <a href="shop.php" style="color:#c82333; text-decoration:underline;">Shop now</a>
        </div>
    <?php else: ?>
        <form method="post">
        <div class="book-grid">
            <?php 
            $total = 0;
            foreach($_SESSION['cart'] as $book_id => $qty):
                $book_id = intval($book_id);
                $res = $conn->query("SELECT * FROM book WHERE BookID=$book_id LIMIT 1");
                if($res && $res->num_rows > 0):
                    $book = $res->fetch_assoc();
                    $subtotal = $book['Price'] * $qty;
                    $total += $subtotal;
            ?>
            <div class="book-card">
                <div class="badge">In Cart</div>
                <!-- Use images/ folder for your downloaded images -->
                <img src="images/<?php echo htmlspecialchars($book['Image']); ?>" alt="<?php echo htmlspecialchars($book['BookTitle']); ?>">
                <h3><?php echo htmlspecialchars($book['BookTitle']); ?></h3>
                <div class="stars">★★★★★</div>
                <div class="price">₱<?php echo number_format($book['Price'], 2); ?></div>

                <div class="quantity-selector">
                    <label for="qty_<?php echo $book_id; ?>">Quantity:</label>
                    <input type="number" name="quantities[<?php echo $book_id; ?>]" id="qty_<?php echo $book_id; ?>" value="<?php echo $qty; ?>" min="1" max="100">
                </div>

                <div class="subtotal">Subtotal: ₱<?php echo number_format($subtotal, 2); ?></div>

                <div style="margin-top:0.5rem;">
                    <a href="cart.php?remove=<?php echo $book_id; ?>" class="view-details-btn" style="background:#e74c3c;">Remove</a>
                </div>
            </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>

        <div style="margin-top:2rem; text-align:right;">
            <strong>Total: ₱<?php echo number_format($total, 2); ?></strong>
        </div>

        <div style="margin-top:1rem; text-align:right;">
            <button type="submit" name="update_cart" class="modal-add-to-cart">Update Cart</button>
            <a href="checkout.php" class="modal-add-to-cart" style="background:#28a745;">Proceed to Checkout</a>
        </div>
        </form>
    <?php endif; ?>
</section>

</body>
</html>