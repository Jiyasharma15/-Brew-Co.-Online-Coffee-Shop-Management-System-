<?php

session_start(); // Start session

include 'dbconnect.php'; // Database connection

$error_message = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the request is a POST method
    $email = trim($_POST['email']); // Gets the mail entered by the user
    $password = $_POST['password']; // Gets the password entered by the user

    // Query to check if user exists by register

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } else {
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) { //password verify
                session_regenerate_id(true); // Secure session handling
                $_SESSION['user_id'] = $user_id; //store mail 
                header("Location: menu.php");
                exit();
            } else {
                $error_message = "Incorrect email or password!";
            } 
        } else {
            $error_message = "Incorrect email or password!";
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
    <title>Login - Brew & Co.</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <div class="logo">
                <a href="https://freeimage.host/i/3oOfE9p">
                    <img src="https://iili.io/3oOfE9p.png" alt="Brew & Co. Logo" width="140">
                </a>
            </div>
            <h2>Welcome Back to Brew & Co.</h2>

            <?php if (!empty($error_message)) : ?>
                <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error_message); ?></p>
            <?php endif; ?><br>

            <form method="POST">
                <label for="email">Email <span style="color: red;">*</span></label><br>
                <input type="email" id="email" name="email" placeholder="Enter Email" required><br><br>

                <label for="password">Password <span style="color: red;">*</span></label><br>
                <input type="password" id="password" name="password" placeholder="Enter Password" required><br><br>

                <button type="submit">Login</button><br><br>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>