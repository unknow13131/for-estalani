<?php
session_start();
include 'db.php'; // your database connection

// ==================== ADD TO CART LOGIC ====================
if(isset($_POST['ac'])) {
    $book_id = intval($_POST['ac']); // Make sure it's an integer
    $quantity = intval($_POST['quantity']);

    if($quantity > 0) {
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array(); // Initialize cart array
        }

        // If the book is already in the cart, update quantity
        if(isset($_SESSION['cart'][$book_id])) {
            $_SESSION['cart'][$book_id] += $quantity;
        } else {
            $_SESSION['cart'][$book_id] = $quantity;
        }

        // Optional: redirect directly to cart.php after adding
        header("Location: cart.php");
        exit; // Important to stop further execution
    }
}
// ===========================================================
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Inkspired Book Shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="shop-page">

<div class="top-bar">
    <div class="container">
        <div class="contact-info">
            <span><i class="fa fa-phone"></i> +208-6666-0112</span>
            <span><i class="fa fa-envelope"></i> info@example.com</span>
            <span>Sunday - Fri: 9 am - 6 pm</span>
        </div>
        <div class="social-login">
            <a href="#" title="Live Chat"><i class="fa fa-comments"></i> Live Chat</a>
        </div>
    </div>
</div>

<header class="main-header">
    <div class="logo"><a href="index.php" style="color: white; text-decoration: none;">Welcome to Inkspired Book</a></div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <div class="dropdown">
            <a href="shop.php">Shop <i class="fa fa-caret-down"></i></a>
            <div class="dropdown-content">
                <a href="#">Shop Details</a>
                <a href="#">About Us</a>
                <a href="#">Cart</a>
                <a href="#">Checkout</a>
            </div>
        </div>
        <a href="#">Pages</a>
        <a href="#">Blog</a>
        <a href="contact.php">Contact</a>
        <?php if(isset($_SESSION['id'])): ?>
            <a href="logout.php" class="nav-logout">Logout</a>
        <?php else: ?>
            <a href="signin.php" class="nav-login">Login</a>
        <?php endif; ?>
    </nav>
    <div class="user-actions">
        <form class="search-form" action="shop.php" method="get">
            <input type="text" name="q" placeholder="Search...">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        <a href="#" class="icon"><i class="fa fa-heart"></i></a>
        <a href="cart.php" class="cart-link"><i class="fa fa-shopping-cart cart-icon"></i> <?php echo isset($_SESSION['cart']) ? '(' . count($_SESSION['cart']) . ')' : ''; ?></a>
    </div>
</header>

<section class="hero-section">
    <div class="container">
        <div class="hero-text">
            <h1>Get Your New Book With The Best Price</h1>
            <p class="subhead">Up To 30% Off</p>
            <a href="shop.php" class="shop-now">Shop Now</a>
        </div>
        <div class="hero-image">
            <img src="https://tse3.mm.bing.net/th/id/OIP.cfoOVlD7W8Ecpp4FTRanFQHaE7?pid=Api&P=0&h=220" alt="Woman holding book">
        </div>
    </div>
</section>

<section class="value-bar">
    <div class="container">
        <div class="value-item">
            <i class="fa fa-undo"></i>
            <p>Return &amp; Refund</p>
        </div>
        <div class="value-item">
            <i class="fa fa-lock"></i>
            <p>Secure Payment</p>
        </div>
        <div class="value-item">
            <i class="fa fa-headset"></i>
            <p>Quality Support</p>
        </div>
        <div class="value-item">
            <i class="fa fa-tags"></i>
            <p>Daily Offers</p>
        </div>
    </div>
</section>

<section class="featured-books">
    <?php if(!isset($_SESSION['id'])): ?>
        <div style="background: rgba(255,255,255,0.8); padding:1rem; border-radius:8px; text-align:center; margin-bottom:2rem;">
            <span style="color: #800000; font-weight:600;">New here?</span>
            <a href="signin.php" style="color: #E74C3C; font-weight:600; text-decoration:underline;">Sign in or register</a> to start shopping!
        </div>
    <?php endif; ?>

    <h2>Featured &amp; Top Selling Books</h2>

    <?php if(isset($message)): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; text-align: center; margin-bottom: 2rem;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="book-grid">
       

        <div class="book-card">
            <div class="badge">Hot</div>
            <img src="https://scontent.fmnl17-5.fna.fbcdn.net/v/t1.15752-9/641776126_1155254796577987_491221493164661189_n.jpg?stp=dst-jpg_s2048x2048_tt6&_nc_cat=110&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeEgSUJxpe_vzEXZtaY_v4O00X_7Rxvu5k7Rf_tHG-7mTt_6hWdheRk7ol1RqjfZJVKa8qCgCewMRJ9JMsuk9cla&_nc_ohc=3j2uev3YUvUQ7kNvwErtMlD&_nc_oc=Admhp7jL2Ghy5EJZ84NjIubmlMUYx21EMkRDGYY3ihZMv2LeVwFA48MXsVDIFT4ZekI&_nc_zt=23&_nc_ht=scontent.fmnl17-5.fna&_nc_ss=8&oh=03_Q7cD4wG4iVrwAmN_vA1ocEMf01gD3hVuewZo6OieOZQtWzvG-Q&oe=69DC7CAE" alt="Diko Alm">
            <h3>Diko Alm Book</h3>
            <div class="stars">★★★★★</div>
            <div class="price">₱100.00</div>
            <button class="view-details-btn" onclick="openModal(101, 'Diko Alm Book', 'https://scontent.fmnl17-5.fna.fbcdn.net/v/t1.15752-9/641776126_1155254796577987_491221493164661189_n.jpg?stp=dst-jpg_s2048x2048_tt6&_nc_cat=110&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeEgSUJxpe_vzEXZtaY_v4O00X_7Rxvu5k7Rf_tHG-7mTt_6hWdheRk7ol1RqjfZJVKa8qCgCewMRJ9JMsuk9cla&_nc_ohc=3j2uev3YUvUQ7kNvwErtMlD&_nc_oc=Admhp7jL2Ghy5EJZ84NjIubmlMUYx21EMkRDGYY3ihZMv2LeVwFA48MXsVDIFT4ZekI&_nc_zt=23&_nc_ht=scontent.fmnl17-5.fna&_nc_ss=8&oh=03_Q7cD4wG4iVrwAmN_vA1ocEMf01gD3hVuewZo6OieOZQtWzvG-Q&oe=69DC7CAE', 100, 'Experience the extraordinary journey through pages filled with wonder and discovery. This masterpiece will captivate your imagination.')">View Details</button>
        </div>

        <div class="book-card">
            <div class="badge">Hot</div>
            <img src="https://scontent.fmnl17-5.fna.fbcdn.net/v/t1.15752-9/642382630_1625800631877531_3042249210264091433_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeGjkIN7LOzzzHM9k_ILC525j_dxZw-GNGOP93FnD4Y0Y1cgY6c7c1d8dU9hPNNSXcvLwIFd6VKk1kvQQNBWID6w&_nc_ohc=xyx0YHUfmEcQ7kNvwH76SO_&_nc_oc=AdkD3hxD0N5mmGHrPcOthBexlmH2Cd_ToPx0mJN391HYIJvjFv1x78DEErkYc1Ja_Sw&_nc_zt=23&_nc_ht=scontent.fmnl17-5.fna&_nc_ss=8&oh=03_Q7cD4wFUVtdWfNAlvedXVN3s7Ed0qGlEKUW-l_nyhUdKF8hHyA&oe=69DC835E" alt="Diko Alm">
            <h3>Pride - Edition 2</h3>
            <div class="stars">★★★★★</div>
            <div class="price">₱100.00</div>
            <button class="view-details-btn" onclick="openModal(102, 'Pride - Edition 2', 'https://scontent.fmnl17-5.fna.fbcdn.net/v/t1.15752-9/642382630_1625800631877531_3042249210264091433_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeGjkIN7LOzzzHM9k_ILC525j_dxZw-GNGOP93FnD4Y0Y1cgY6c7c1d8dU9hPNNSXcvLwIFd6VKk1kvQQNBWID6w&_nc_ohc=xyx0YHUfmEcQ7kNvwH76SO_&_nc_oc=AdkD3hxD0N5mmGHrPcOthBexlmH2Cd_ToPx0mJN391HYIJvjFv1x78DEErkYc1Ja_Sw&_nc_zt=23&_nc_ht=scontent.fmnl17-5.fna&_nc_ss=8&oh=03_Q7cD4wFUVtdWfNAlvedXVN3s7Ed0qGlEKUW-l_nyhUdKF8hHyA&oe=69DC835E', 100, 'Dive deeper into the enchanting world with this special edition. A literary treasure that will stay with you forever.')">View Details</button>
        </div>

        <div class="book-card">
            <div class="badge">Hot</div>
            <img src="https://scontent.fmnl17-7.fna.fbcdn.net/v/t1.15752-9/648163797_1916050589033694_7409185078121489673_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeGRpveIfoGzq3saWNmaD9BNbntNj43YYY9ue02Pjdhhj82FZtp1Kv3mkhWKApLAyBLtgeag-EMthI2Nu71iMLGp&_nc_ohc=Xhu2HmgkJOgQ7kNvwH69G__&_nc_oc=AdkEzt7AE-CTyreoRmiL43MKh1U8tSLtcj7ysDY63egSuXMbNZt8fJVm6x6KmkhiWtw&_nc_zt=23&_nc_ht=scontent.fmnl17-7.fna&_nc_ss=8&oh=03_Q7cD4wH34OgCP6Vdjx1uALSkNPmesyL7iQ2wAh2OgXUoOenboQ&oe=69DC810F" alt="Diko Alm">
            <h3>Diko Alm Book - Collector's Edition</h3>
            <div class="stars">★★★★★</div>
            <div class="price">₱100.00</div>
            <button class="view-details-btn" onclick="openModal(103, 'Diko Alm Book - Collector\'s Edition', 'https://scontent.fmnl17-7.fna.fbcdn.net/v/t1.15752-9/648163797_1916050589033694_7409185078121489673_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeGRpveIfoGzq3saWNmaD9BNbntNj43YYY9ue02Pjdhhj82FZtp1Kv3mkhWKApLAyBLtgeag-EMthI2Nu71iMLGp&_nc_ohc=Xhu2HmgkJOgQ7kNvwH69G__&_nc_oc=AdkEzt7AE-CTyreoRmiL43MKh1U8tSLtcj7ysDY63egSuXMbNZt8fJVm6x6KmkhiWtw&_nc_zt=23&_nc_ht=scontent.fmnl17-7.fna&_nc_ss=8&oh=03_Q7cD4wH34OgCP6Vdjx1uALSkNPmesyL7iQ2wAh2OgXUoOenboQ&oe=69DC810F', 100, 'The ultimate collector\'s edition featuring exclusive content and stunning illustrations. A must-have for true book lovers.')">View Details</button>
        </div>
    </div>
</section>

<section class="top-categories">
    <h2>Top Categories</h2>
    <div class="categories-grid">
        <div class="category-item">
            <div class="category-icon">
                <img src="https://tse2.mm.bing.net/th/id/OIP.jBHBujRChbN7fHwWCx1ADgHaL3?pid=Api&P=0&h=220" alt="Romance">
            </div>
            <h3>Romance Books</h3>
            <p>80</p>
        </div>

        <div class="category-item">
            <div class="category-icon">
                <img src="https://media.licdn.com/dms/image/v2/D4E22AQFTIqGmLlIx7g/feedshare-shrink_2048_1536/feedshare-shrink_2048_1536/0/1690886336274?e=2147483647&v=beta&t=CcmwMAf_6_bnGsxlOPD10nUAnHyoEkwgC_vLuiUB8O4" alt="Design">
            </div>
            <h3>Design Books</h3>
            <p>60</p>
        </div>

        <div class="category-item">
            <div class="category-icon">
                <img src="https://images.pangobooks.com/images/35d9d6d0-054e-4116-a384-39ff3a76d0ba?quality=85&width=1200&crop=1:1" alt="Business">
            </div>
            <h3>Business Books</h3>
            <p>45</p>
        </div>

        <div class="category-item">
            <div class="category-icon">
                <img src="https://kitaabnow.com/wp-content/uploads/2023/02/9780199063680-Kitaabnow.com_.jpg" alt="Science">
            </div>
            <h3>Science Books</h3>
            <p>30</p>
        </div>
    </div>
</section>

<section class="testimonials">
    <h2>What Our Clients Say</h2>
    <div class="quote-item">
        <p>"Great selection and fast delivery!"</p>
        <strong>- khane gomez </strong>
    </div>
    <div class="quote-item">
        <p>"Amazing customer support."</p>
        <strong>- nash angeles</strong>
    </div>
</section>

<section class="authors">
    <div class="author-card">
        <img src="https://scontent.fmnl17-7.fna.fbcdn.net/v/t39.30808-1/557910196_122180062736472042_4063656736953997068_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=108&ccb=1-7&_nc_sid=e99d92&_nc_eui2=AeEIKmLisU5SaL0oKgUfqDRlJjkYS-FJ_o4mORhL4Un-jrkGJvo4hkWmbtGCOgp8QlmY9ABup_byBPlQD3xBfv53&_nc_ohc=uMHnGE0ibsIQ7kNvwFrTH8P&_nc_oc=Adm2o1k1JFq6STqKsQjgWpdtHBnNQA2zcpZJX1Wj2Xifm_jHK9zTLCrHu-7uJZe9aFM&_nc_zt=24&_nc_ht=scontent.fmnl17-7.fna&_nc_gid=XA9bChCpto_cHb69cuBptQ&_nc_ss=8&oh=00_Afypr7p8_Rs2yqIP2CHrLTJQWKuQ5-1bywvMkdHKXF4d-w&oe=69BCF048" alt="Author 1">
        <p>lawrence<br><small>8 Books</small></p>
    </div>
    <div class="author-card">
        <img src="https://scontent.fmnl17-3.fna.fbcdn.net/v/t39.30808-1/504192323_718691443999943_1079337636259887341_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=103&ccb=1-7&_nc_sid=e99d92&_nc_eui2=AeGP690duLkbV_52bR6m2TnfhtqQgqfYf8iG2pCCp9h_yEXbTJCsTOc79IdEhmYjglbcjGtVPLsQHCeDduWbpgVN&_nc_ohc=lV-PEVTchiUQ7kNvwGpPPqK&_nc_oc=AdnQaNBLYiYR-efuvFADIEps_EoWCmB2H1p5_Qph2UuVMZmZtdplp-0R3e_ZOq8crXk&_nc_zt=24&_nc_ht=scontent.fmnl17-3.fna&_nc_gid=ci219KxM7MxFlBoztvT0sg&_nc_ss=8&oh=00_AfyOy0v26eh3cGQoK1r8g0XYyVfjeQzS2J3xMtHucy28UQ&oe=69BCF06E" alt="Author 2">
        <p>christian estalani<br><small>12 Books</small></p>
    </div>
</section>

<section class="latest-news">
    <h2>Latest News</h2>
    <div class="news-grid">
        <div class="news-item">
            <img src="https://scontent.fmnl17-1.fna.fbcdn.net/v/t39.30808-1/485177972_665481392528783_7147178422224692651_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=100&ccb=1-7&_nc_sid=1d2534&_nc_eui2=AeHJ7KMtAg0HetQtv6SXoXdkUnUHJ6a8ZFBSdQcnprxkUN2dyS3_EO2JxkWCykfTDzyXvhxA-DW_PHutJvbk_tjG&_nc_ohc=ru9ZxHseLsQQ7kNvwFdzniY&_nc_oc=AdkwiOCty3MTr6l-5QKSOYgYJLB5sEBhTRxTsQoPjcUMbABdW9hnqE6XsYoDJCdc80g&_nc_zt=24&_nc_ht=scontent.fmnl17-1.fna&_nc_gid=OZ_rgTYOQEusyltsPz5c_g&_nc_ss=8&oh=00_AfzhusdWUnhhmh0HeIQKmMkk0SdW8T085R07WNnU7ybpYA&oe=69BCE956" alt="">
            <p>Jan 1, 2026 by Admin</p>
            <h3>New Arrivals This Month</h3>
            <a href="#">adrian gozon</a>
        </div>
        <div class="news-item">
            <img src="images/news2.jpg" alt="">
            <p>Feb 15, 2026 by Admin</p>
            <h3>How to Choose a Book</h3>
            <a href="#">Read More</a>
        </div>
        <div class="news-item">
            <img src="images/news3.jpg" alt="">
            <p>Mar 3, 2026 by Admin</p>
            <h3>Interview with an Author</h3>
            <a href="#">Read More</a>
        </div>
    </div>
</section>

<div id="bookModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-grid">
            <div class="modal-left">
                <img id="modalBookImage" src="" alt="Book Cover">
            </div>
            <div class="modal-right">
                <h2 id="modalBookTitle"></h2>
                <div class="modal-stars">★★★★★</div>
                <div class="modal-price" id="modalBookPrice"></div>
                <p class="modal-description" id="modalBookDescription"></p>

                <form method="post" class="modal-cart-form">
                    <div class="quantity-selector">
                        <label for="modalQuantity">Quantity:</label>
                        <input type="number" id="modalQuantity" name="quantity" value="1" min="1" max="100">
                    </div>
                    <input type="hidden" id="modalBookId" name="ac" value="">
                    <button type="submit" class="modal-add-to-cart">Add To Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container footer-links">
        <div>
            <h4>Contact</h4>
            <p>Phone: 123-456-7890</p>
            <p>Email: info@inkspired.com</p>
            <p>Hours: 9am - 6pm</p>
        </div>
        <div>
            <h4>Location</h4>
            <p>123 Book St.<br>Library City</p>
        </div>
        <div>
            <h4>Customer Support</h4>
            <a href="#">Store List</a>
            <a href="#">Help Center</a>
            <a href="#">FAQs</a>
        </div>
    </div>
</footer>

<script>
function openModal(id, title, image, price, description) {
    document.getElementById('bookModal').style.display = 'block';
    document.getElementById('modalBookId').value = id;
    document.getElementById('modalBookTitle').textContent = title;
    document.getElementById('modalBookImage').src = image;
    document.getElementById('modalBookPrice').textContent = '₱' + price.toFixed(2);
    document.getElementById('modalBookDescription').textContent = description;
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('bookModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function redirectToCart() {
    setTimeout(function() {
        window.location.href = 'cart.php';
    }, 300);
}

window.onclick = function(event) {
    const modal = document.getElementById('bookModal');
    if (event.target === modal || event.target.classList.contains('modal-overlay')) {
        closeModal();
    }
}
</script>

</body>
</html>