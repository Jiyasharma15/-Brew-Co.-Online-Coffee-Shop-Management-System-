<?php
session_start();
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];
?>

<!DOCTYPE html>
    <header><br>
        <a href="https://freeimage.host/i/3oOfE9p">
    <img src="https://iili.io/3oOfE9p.png" alt="3oOfE9p.png" width="160" height="160"></a>
    <h2>Brew & Co.</h2><br><br>
</header>
<head>
    <title>Order Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #FFFFF0;
        }
        .success-message {
            font-size: 24px;
            color: green;
        }
        .button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #218838;

    </style>
</head>
<body>
    <h2>Thank You for Your Ordering from Brew & Co.!</h2><br>
    <h1 class="success-message">Order Placed Successfully!</h1><br>
    <p>Your order is under process. It will be delivered soon</p>
    <strong><p>Your order ID is: <?php echo htmlspecialchars($order_id); ?></p></strong>
    <a href="menu.php" class="button">Go Back to Menu</a><br><br><br><br>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Brew & Co. All rights reserved.</p> 
    </footer>

</body>
</html>
