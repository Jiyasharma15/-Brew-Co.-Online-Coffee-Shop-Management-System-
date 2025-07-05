<?php
session_start();
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_email'])) {
        header("Location: login.php"); // Redirect to login page if not logged in
        exit();
    }

    // Capture the shipping address and payment method from the form
    $shipping_address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    // Generate a unique Order ID
    function generateOrderId() {
        return strtoupper(bin2hex(random_bytes(5))); // Generates a 10-character random string
    }

    $order_id = generateOrderId();

    // Get the logged-in user's email
    $user_email = $_SESSION['user_email'];

    // Calculate the total price (assuming it's already calculated in the session)
    $total_price = $_SESSION['final_total']; // Ensure this is set before

    // Set the order status to "Pending" initially
    $order_status = 'Pending';

    // Insert the order into the database
    $stmt = $pdo->prepare("INSERT INTO orders (order_id, user_email, total_price, shipping_address, payment_method, order_status) 
                           VALUES (:order_id, :user_email, :total_price, :shipping_address, :payment_method, :order_
