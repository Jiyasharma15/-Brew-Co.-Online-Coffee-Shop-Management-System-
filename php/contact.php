<?php
    $title = "Brew & Co. Contact Us"; //store title

    $email = "brew&co@gmail.com"; //store mail

    $phone = "+91 11-1515XXXX"; //store phone no of brew
?>

<!DOCTYPE html>
<head>
    <title>Brew & Co. Contact Us</title>
    <link rel="stylesheet" href="contact.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <header>
        <a href="https://freeimage.host/i/3oOfE9p">
    <img src="https://iili.io/3oOfE9p.png" alt="3oOfE9p.png" width="160" height="160"></a>
        <h2>Welcome to Brew & Co.</h2>
        <nav>
            <a href="index.php">Home</a>
            <a href="menu.php">Order</a>
        </nav>
    </header>
    <section>
        <center>
            <h1>Contact Us</h1><br>
        <p><i class="material-icons" style="font-size:38px;color:black; vertical-align: middle;">mail</i>  <?php echo $email; ?></p>
        <p><i class="material-icons" style="font-size:38px;color:black; vertical-align: middle;">phone</i>  <?php echo $phone; ?></p><br>
          </p><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

        </center>
    </section>
    </center>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Brew & Co. All rights reserved.</p>
    </footer>
</body>
</html>
