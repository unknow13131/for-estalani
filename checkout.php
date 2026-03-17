<?php
session_start();
include 'db.php';

// Check if user is logged in
if(!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

// Check if cart is empty
if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = '';
$error = '';
$success = false;

// Get user info
$stmt = $conn->prepare("SELECT Email, FirstName, LastName FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Handle checkout submission
if(isset($_POST['place_order'])) {
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $shipping_address = htmlspecialchars(trim($_POST['shipping_address']));
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));

    // Validate fields
    if(empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address) || empty($shipping_address)) {
        $error = "All fields are required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif(!preg_match('/^(\+63|0)[0-9]{9,10}$/', preg_replace('/[^0-9+]/', '', $phone))) {
        $error = "Please enter a valid phone number (e.g., +63 9XX XXX XXXX or 09XX XXX XXXX).";
    } elseif(strlen($address) < 10) {
        $error = "Please enter a complete street address (at least 10 characters).";
    } elseif(strlen($shipping_address) < 10) {
        $error = "Please enter a complete shipping address (at least 10 characters).";
    } else {
        $total_price = 0;
        $order_items = array();

        // Calculate total and gather items
        foreach($_SESSION['cart'] as $book_id => $quantity) {
            $book_id = intval($book_id);
            $quantity = intval($quantity);
            $stmt = $conn->prepare("SELECT BookID, BookTitle, Price FROM book WHERE BookID = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result && $result->num_rows > 0) {
                $book = $result->fetch_assoc();
                $item_total = $book['Price'] * $quantity;
                $total_price += $item_total;
                $order_items[] = array(
                    'book_id' => $book_id,
                    'book_title' => $book['BookTitle'],
                    'quantity' => $quantity,
                    'price' => $book['Price'],
                    'subtotal' => $item_total
                );
            }
            $stmt->close();
        }

        if(!empty($order_items)) {
            $order_date = date('Y-m-d H:i:s');

            // Insert order
            $stmt_order = $conn->prepare("INSERT INTO orders (user_id, OrderDate, TotalPrice, Status, DeliveryAddress, PaymentMethod) VALUES (?, ?, ?, 'Pending', ?, ?)");
            $stmt_order->bind_param("isdss", $user_id, $order_date, $total_price, $shipping_address, $payment_method);

            if($stmt_order->execute()) {
                $order_id = $conn->insert_id;

                // Insert order items
                foreach($order_items as $item) {
                    $stmt_items = $conn->prepare("INSERT INTO order_items (OrderID, BookID, Quantity, Price) VALUES (?, ?, ?, ?)");
                    $stmt_items->bind_param("iiid", $order_id, $item['book_id'], $item['quantity'], $item['price']);
                    $stmt_items->execute();
                    $stmt_items->close();
                }

                unset($_SESSION['cart']);
                $message = "Order placed successfully! Order #" . $order_id;
                $success = true;
            } else {
                $error = "Error placing order. Please try again.";
            }
            $stmt_order->close();
        }
    }
}

// Calculate cart total for display
$total = 0;
if(!empty($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $book_id => $quantity) {
        $book_id = intval($book_id);
        $quantity = intval($quantity);
        $stmt = $conn->prepare("SELECT Price FROM book WHERE BookID = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result && $result->num_rows > 0) {
            $book = $result->fetch_assoc();
            $total += $book['Price'] * $quantity;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Inkspired Book Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5ede1 0%, #e8dcc8 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .checkout-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6b5344;
            text-decoration: none;
            margin-bottom: 30px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-button:hover {
            color: #8b6f47;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .checkout-header h1 {
            font-size: 2.5em;
            color: #6b5344;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .checkout-header p {
            color: #8b7355;
            font-size: 1.05em;
        }

        .success-container {
            background: white;
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(107, 83, 68, 0.1);
        }

        .success-icon {
            font-size: 4em;
            color: #7fb069;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-message h2 {
            color: #6b5344;
            font-size: 1.8em;
            margin-bottom: 15px;
        }

        .success-message p {
            color: #8b7355;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .success-message a {
            display: inline-block;
            background: linear-gradient(135deg, #a67c52 0%, #8b6f47 100%);
            color: white;
            padding: 12px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .success-message a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(167, 124, 82, 0.3);
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(107, 83, 68, 0.08);
        }

        .form-section h2 {
            font-size: 1.4em;
            color: #6b5344;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0e6d2;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        label {
            display: block;
            color: #6b5344;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8dcc8;
            border-radius: 6px;
            font-size: 1em;
            color: #6b5344;
            font-family: inherit;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #fdfbf8;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #a67c52;
            box-shadow: 0 0 0 3px rgba(166, 124, 82, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #e8dcc8;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #fdfbf8;
        }

        .payment-option:hover {
            border-color: #a67c52;
            background-color: #faf7f2;
        }

        .payment-option input[type="radio"] {
            cursor: pointer;
            width: 20px;
            height: 20px;
            margin-right: 12px;
            accent-color: #a67c52;
        }

        .payment-option label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
            flex: 1;
        }

        .order-summary {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(107, 83, 68, 0.08);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .order-summary h3 {
            font-size: 1.3em;
            color: #6b5344;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0e6d2;
            font-weight: 600;
        }

        .order-items {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0e6d2;
            color: #8b7355;
            font-size: 0.95em;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            color: #6b5344;
            font-weight: 500;
            display: block;
            margin-bottom: 4px;
        }

        .item-qty {
            font-size: 0.85em;
            color: #a67c52;
        }

        .item-price {
            font-weight: 600;
            color: #6b5344;
            min-width: 70px;
            text-align: right;
        }

        .summary-section {
            padding: 15px 0;
            border-bottom: 1px solid #f0e6d2;
            color: #8b7355;
            display: flex;
            justify-content: space-between;
        }

        .summary-section.total {
            border-bottom: none;
            border-top: 2px solid #a67c52;
            padding-top: 20px;
            font-size: 1.3em;
            font-weight: 700;
            color: #6b5344;
            margin-top: 15px;
        }

        .summary-label {
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #a67c52 0%, #8b6f47 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.05em;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-top: 25px;
            letter-spacing: 0.5px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(167, 124, 82, 0.3);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #f5c6cb;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .checkout-header h1 {
                font-size: 1.8em;
            }

            .form-section {
                padding: 25px;
            }
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f0e6d2;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #a67c52;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #8b6f47;
        }

        .error-field {
            border-color: #f5c6cb !important;
            background-color: #fff8f8 !important;
        }

        .field-error-message {
            color: #721c24;
            font-size: 0.85em;
            margin-top: 5px;
            display: none;
        }

        .field-error-message.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="checkout-wrapper">
        <?php if($success): ?>
            <div class="success-container">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="success-message">
                    <h2><?php echo $message; ?></h2>
                    <p>Thank you for your order! We'll process it shortly and send you a confirmation email at <?php echo htmlspecialchars($email); ?>.</p>
                    <p style="font-size: 0.9em; color: #a67c52; margin-bottom: 20px;">Payment method: Cash on Delivery</p>
                    <a href="shop.php"><i class="fas fa-shopping-bag"></i> Continue Shopping</a>
                </div>
            </div>
        <?php else: ?>
            <a href="cart.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>

            <div class="checkout-header">
                <h1>Checkout</h1>
                <p>Complete your purchase and we'll get your books to you soon</p>
            </div>

            <?php if(!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="checkout-content">
                <form method="post" class="form-section">
                    <h2><i class="fas fa-user"></i> Billing Information</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Full Name *</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['FirstName'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['LastName'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>" required>
                            <div class="field-error-message" id="email-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" placeholder="+63 9XX XXX XXXX" required>
                            <div class="field-error-message" id="phone-error"></div>
                        </div>
                    </div>

                    <h2 style="margin-top: 35px;"><i class="fas fa-map-marker-alt"></i> Billing Address</h2>

                    <div class="form-group">
                        <label for="address">Street Address *</label>
                        <textarea id="address" name="address" placeholder="Enter your street address" required></textarea>
                        <div class="field-error-message" id="address-error"></div>
                    </div>

                    <h2 style="margin-top: 35px;"><i class="fas fa-truck"></i> Shipping Address</h2>

                    <div class="form-group">
                        <label for="shipping_address">Shipping Address *</label>
                        <textarea id="shipping_address" name="shipping_address" placeholder="Enter your shipping address" required></textarea>
                        <div class="field-error-message" id="shipping-error"></div>
                    </div>

                    <h2 style="margin-top: 35px;"><i class="fas fa-credit-card"></i> Payment Method</h2>

                    <div class="form-group">
                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="Cash on Delivery" checked required>
                                <label style="margin: 0;">Cash on Delivery (COD)</label>
                            </label>
                        </div>
                    </div>

                    <button type="submit" name="place_order" class="submit-btn">
                        <i class="fas fa-lock"></i> Place Order - ₱<?php echo number_format($total, 2); ?>
                    </button>
                </form>

                <div class="order-summary">
                    <h3><i class="fas fa-receipt"></i> Order Summary</h3>

                    <div class="order-items">
                        <?php
                        $item_count = 0;
                        if(!empty($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $book_id => $quantity) {
                                $book_id = intval($book_id);
                                $quantity = intval($quantity);
                                $stmt = $conn->prepare("SELECT BookTitle, Price FROM book WHERE BookID = ?");
                                $stmt->bind_param("i", $book_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result && $result->num_rows > 0) {
                                    $book = $result->fetch_assoc();
                                    $subtotal = $book['Price'] * $quantity;
                                    $item_count++;
                        ?>
                                    <div class="order-item">
                                        <div class="item-details">
                                            <span class="item-name"><?php echo htmlspecialchars($book['BookTitle']); ?></span>
                                            <span class="item-qty">Qty: <?php echo $quantity; ?></span>
                                        </div>
                                        <div class="item-price">₱<?php echo number_format($subtotal, 2); ?></div>
                                    </div>
                        <?php
                                }
                                $stmt->close();
                            }
                        }
                        ?>
                    </div>

                    <div class="summary-section">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">₱<?php echo number_format($total, 2); ?></span>
                    </div>

                    <div class="summary-section">
                        <span class="summary-label">Shipping Fee</span>
                        <span class="summary-value" style="color: #7fb069;">FREE</span>
                    </div>

                    <div class="summary-section total">
                        <span class="summary-label">Total Amount</span>
                        <span class="summary-value">₱<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const addressInput = document.getElementById('address');
        const shippingInput = document.getElementById('shipping_address');
        const emailError = document.getElementById('email-error');
        const phoneError = document.getElementById('phone-error');
        const addressError = document.getElementById('address-error');
        const shippingError = document.getElementById('shipping-error');
        const form = document.querySelector('form');

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validatePhone(phone) {
            const cleanPhone = phone.replace(/[^0-9+]/g, '');
            const phoneRegex = /^(\+63|0)[0-9]{9,10}$/;
            return phoneRegex.test(cleanPhone);
        }

        function validateAddress(address) {
            return address.trim().length >= 10;
        }

        function showError(input, errorElement, message) {
            input.classList.add('error-field');
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }

        function hideError(input, errorElement) {
            input.classList.remove('error-field');
            errorElement.textContent = '';
            errorElement.classList.remove('show');
        }

        emailInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                showError(this, emailError, 'Email is required.');
            } else if (!validateEmail(this.value)) {
                showError(this, emailError, 'Please enter a valid email address.');
            } else {
                hideError(this, emailError);
            }
        });

        emailInput.addEventListener('input', function() {
            if (this.value.trim() !== '' && validateEmail(this.value)) {
                hideError(this, emailError);
            }
        });

        phoneInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                showError(this, phoneError, 'Phone number is required.');
            } else if (!validatePhone(this.value)) {
                showError(this, phoneError, 'Please enter a valid phone number (e.g., +63 9XX XXX XXXX or 09XX XXX XXXX).');
            } else {
                hideError(this, phoneError);
            }
        });

        phoneInput.addEventListener('input', function() {
            if (this.value.trim() !== '' && validatePhone(this.value)) {
                hideError(this, phoneError);
            }
        });

        addressInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                showError(this, addressError, 'Street address is required.');
            } else if (!validateAddress(this.value)) {
                showError(this, addressError, 'Please enter a complete street address (at least 10 characters).');
            } else {
                hideError(this, addressError);
            }
        });

        addressInput.addEventListener('input', function() {
            if (this.value.trim() !== '' && validateAddress(this.value)) {
                hideError(this, addressError);
            }
        });

        shippingInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                showError(this, shippingError, 'Shipping address is required.');
            } else if (!validateAddress(this.value)) {
                showError(this, shippingError, 'Please enter a complete shipping address (at least 10 characters).');
            } else {
                hideError(this, shippingError);
            }
        });

        shippingInput.addEventListener('input', function() {
            if (this.value.trim() !== '' && validateAddress(this.value)) {
                hideError(this, shippingError);
            }
        });

        form.addEventListener('submit', function(e) {
            let hasErrors = false;

            if (emailInput.value.trim() === '' || !validateEmail(emailInput.value)) {
                showError(emailInput, emailError, emailInput.value.trim() === '' ? 'Email is required.' : 'Please enter a valid email address.');
                hasErrors = true;
            }

            if (phoneInput.value.trim() === '' || !validatePhone(phoneInput.value)) {
                showError(phoneInput, phoneError, phoneInput.value.trim() === '' ? 'Phone number is required.' : 'Please enter a valid phone number.');
                hasErrors = true;
            }

            if (addressInput.value.trim() === '' || !validateAddress(addressInput.value)) {
                showError(addressInput, addressError, addressInput.value.trim() === '' ? 'Street address is required.' : 'Please enter a complete address.');
                hasErrors = true;
            }

            if (shippingInput.value.trim() === '' || !validateAddress(shippingInput.value)) {
                showError(shippingInput, shippingError, shippingInput.value.trim() === '' ? 'Shipping address is required.' : 'Please enter a complete address.');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
