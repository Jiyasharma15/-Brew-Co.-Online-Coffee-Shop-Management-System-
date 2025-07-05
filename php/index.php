<?php
session_start(); //user checking if login track user 
?>

<!DOCTYPE html>
<head>
    <title>Brew & Co.</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <a href="https://freeimage.host/i/3oOfE9p">
    <img src="https://iili.io/3oOfE9p.png" alt="3oOfE9p.png" width="160" height="160"></a>
        <h2>Welcome to Brew & Co.</h2><br>
        <h3>Dashboard</h3>
        <nav>
            <a href="menu.php">Go to Menu</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

    <section>
        <h2>Handcrafted Perfection in Every Cup</h2>
        <p>We serve fresh and organic coffee made with love.</p>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Brew & Co. All rights reserved.</p> 
    </footer>

</body>
</html>