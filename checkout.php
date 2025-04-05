<?php
session_start();
include 'dbconnect.php'; // Ensure your DB connection is correct

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='menu.php'>Go back to Menu</a></p>";
    exit();
}

// Calculate the total price from the cart session
$total_price = 0;
foreach ($_SESSION['cart'] as $product_id => $details) {
    $total_price += $details['price'] * $details['quantity'];
}

// Shipping cost
$shipping_cost = 0;
$final_total = (float)($total_price + $shipping_cost);

// Process the order when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize form data
    $address = htmlspecialchars(trim($_POST['address']));
    $city = htmlspecialchars(trim($_POST['city']));
    $pincode = htmlspecialchars(trim($_POST['pincode']));
    $state = htmlspecialchars(trim($_POST['state']));
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));

    // Validate pincode (assuming it's numeric and 6 digits)
    if (!is_numeric($pincode) || strlen($pincode) != 6) {
        echo "<p>Invalid pincode. Please enter a valid 6-digit pincode.</p>";
        exit();
    }

    // Get the current date and time for the order
    $order_date = date('Y-m-d H:i:s'); // Current date and time

    // Prepare and bind the insert query for orders
    $stmt = $mysqli->prepare("INSERT INTO orders (user_id, total, address, city, pincode, state, payment_method, order_date) VALUES (?, ?, ?, ?, ?, ?, ?,?)");

    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param("idssssss", $user_id, $final_total, $address, $city, $pincode, $state, $payment_method, $order_date);
    
    if (!$stmt->execute()) {
        die("Execution failed: " . $stmt->error);  // If insertion fails, show error message
    }

    $order_id = $stmt->insert_id; // Get last inserted order ID
    $stmt->close(); // Close the statement after execution

    // Insert order items into the order_items table
    foreach ($_SESSION['cart'] as $product_id => $details) {
        // Calculate the subtotal for each product
        $subtotal = $details['price'] * $details['quantity'];

        // Prepare the query for inserting into the order_items table
        $stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, price, quantity, total) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }

        $stmt->bind_param("iiidi", $order_id, $product_id, $details['price'], $details['quantity'], $subtotal);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to order_success.php with the order_id
    header("Location: order_sucess.php?order_id=" . $order_id);
    exit();  // Ensure to stop further execution after the redirect
}
?>

<!-- Checkout Form and Display -->



<!DOCTYPE html>
<head>
    <title>Checkout - Brew & Co.</title>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <header>
        <a href="https://freeimage.host/i/3oOfE9p">
            <img src="https://iili.io/3oOfE9p.png" alt="3oOfE9p.png" width="160" height="160">
        </a>
        <h2> Brew & Co.</h2>
        <h4>Checkout</h4>
    </header>

    <div class="container">
        <section class="order-summary">
            <center>
            <h2>Order Summary</h2>
            </center>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                <?php
                // Fetch product names from the database based on product_id stored in the cart
                foreach ($_SESSION['cart'] as $product_id => $details):
                    // Query to fetch product name based on product_id
                    $stmt = $mysqli->prepare("SELECT name FROM products WHERE product_id = ?");
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $stmt->bind_result($product_name);
                    $stmt->fetch();
                    $stmt->close();
                ?>
                <tr>
                    <td><strong><?php echo $product_name; ?></strong></td>
                    <td>₹<?php echo number_format($details['price'], 2); ?></td>
                    <td><?php echo $details['quantity']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <p><strong>Price : </strong>₹<?php echo number_format($total_price, 2); ?></p>
            <p><strong>Shipping Cost : </strong>₹<?php echo number_format($shipping_cost, 2); ?></p>
            <p><strong>Total Order : </strong>₹<?php echo number_format($final_total, 2); ?></p>
        </section>

        <section class="form-section">
            <form action="checkout.php" method="POST"><br>
                <h3>Shipping Address</h3><br><br>
                <label for="address">Address :</label>
                <br>
                <input type="text" id="address" name="address" required>
                <br><br>

                <label for="city">City:</label>
                <br>
                <input type="text" id="city" name="city" required>
                <br><br>

                <label for="pincode">Pincode:</label>
                <br>
                <input type="text" id="pincode" name="pincode" required>
                <br><br>

                <label for="state">State:</label>
                <br>
                <input type="text" id="state" name="state" required>
                <br><br>

                <h3>Payment Method</h3><br>
                <label for="payment_method">Select Payment Method:</label><br>
                <select name="payment_method" id="payment_method" required>
                    <option value="cod">Cash on Delivery (COD)</option>
                    <option value="upi">UPI</option>
                    <option value="net_banking">Net Banking</option>
                </select>
                <br><br>
                <input type="submit" value="Place Order">
            </form>
        </section>
    </div>

    <footer>
        <p>&copy; 2025 Brew & Co. All rights reserved.</p>
    </footer>

    </body>
    </html>
