?php
session_start();

$message = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message_text = htmlspecialchars($_POST['message']);
    
    // Basic validation
    if(empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $error = 'All fields are required.';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Here you would typically send an email or save to database
        // For now, we'll just show a success message
        $message = '✓ Thank you for your message! We will get back to you soon.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Inkspired Book Shop</title>
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
        <a href="cart.php" class="cart-link"><i class="fa fa-shopping-cart cart-icon"></i> Cart <?php echo isset($_SESSION['cart']) ? '(' . count($_SESSION['cart']) . ')' : '(0)'; ?></a>
    </div>
</header>

<div style="padding: 3rem 2rem; background-color: var(--off-white); min-height: 100vh;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="color: var(--primary-blue); text-align: center; margin-bottom: 3rem;">Contact Us</h1>
        
        <?php if(!empty($message)): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($error)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div style="background-color: var(--white); padding: 2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 3rem;">
            <form method="post" action="contact.php">
                <div style="margin-bottom: 1.5rem;">
                    <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Your Name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="subject" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="Subject of your message" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">Message</label>
                    <textarea id="message" name="message" placeholder="Your message here..." rows="6" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <button type="submit" class="button button-primary" style="width: 100%; padding: 1rem;">Send Message</button>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="background-color: var(--white); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 2rem; margin-bottom: 1rem;">📞</div>
                <h3 style="color: var(--primary-blue); margin-bottom: 0.5rem;">Phone</h3>
                <p>+60123456789</p>
            </div>

            <div style="background-color: var(--white); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 2rem; margin-bottom: 1rem;">📧</div>
                <h3 style="color: var(--primary-blue); margin-bottom: 0.5rem;">Email</h3>
                <p>support@inkspiredbooks.com</p>
            </div>

            <div style="background-color: var(--white); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 2rem; margin-bottom: 1rem;">📍</div>
                <h3 style="color: var(--primary-blue); margin-bottom: 0.5rem;">Address</h3>
                <p>123 Book Street,pasig city</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
