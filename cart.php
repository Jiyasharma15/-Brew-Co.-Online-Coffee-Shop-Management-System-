<?php
session_start();
include 'dbconnect.php';

// Redirect user to login.php if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
        
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    }
    header("Location: cart.php");
    exit();
}

// Handle Remove from Cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

// Calculate total price
$totalPrice = 0;
foreach ($_SESSION['cart'] as $product_id => $details) {
    $totalPrice += $details['price'] * $details['quantity'];
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="cart.css">
    <title>Your Cart</title>
</head>
<body>
    <header>
        <a href="index.php"><img src="https://iili.io/3oOfE9p.png" alt="Logo" width="160" height="160"></a>
        <h2>Brew & Co.</h2>
        <nav>
            <a href="index.php">Home</a>
            <a href="menu.php">Order</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>
    
    <div class="cart-container">
        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($_SESSION['cart'] as $product_id => $details): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($details['name']); ?></td>
                        <td><?php echo $details['quantity']; ?></td>
                        <td>₹<?php echo number_format($details['price'], 2); ?></td>
                        <td>₹<?php echo number_format($details['price'] * $details['quantity'], 2); ?></td>
                        <td><a href="cart.php?action=remove&product_id=<?php echo $product_id; ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="cart-summary">
                <p><strong>Total Price: ₹<?php echo number_format($totalPrice, 2); ?></strong></p>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty. <a href="menu.php">Shop now</a>.</p>
        <?php endif; ?>
    </div>
    
    <center>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Brew & Co. All rights reserved.</p>
    </footer>
</center>
</body>
</html>
