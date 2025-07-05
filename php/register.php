<?php
include 'dbconnect.php'; // Connect to the database
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Checking if form submitted

    $email = trim($_POST["email"]); // Trim email to remove extra spaces
    $password = $_POST["password"]; // Get password from form
    $confirm_password = $_POST["confirm_password"]; // Get confirm password from form

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p style='color: red; text-align: center;'>Invalid email format.</p>";
    } elseif ($password !== $confirm_password) {
        $message = "<p style='color: red; text-align: center;'>Password and Confirm Password do not match.</p>";
    } else {
        // Check if email already exists
        $check_email_query = $mysqli->prepare("SELECT email FROM users WHERE email = ?");
        $check_email_query->bind_param("s", $email);
        $check_email_query->execute();
        $check_email_query->store_result();

        if ($check_email_query->num_rows > 0) {
            $message = "<p style='color: red; text-align: center;'>This email is already registered. Try logging in!</p>";
        } else {
            // Hash password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $created_at = date("Y-m-d H:i:s"); // Store registration timestamp

            $stmt = $mysqli->prepare("INSERT INTO users (email, password, created_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashed_password, $created_at);

            if ($stmt->execute()) {
                $message = "<p style='color: green; text-align: center;'>Registration successful! Redirecting to login...</p>";
                header("refresh:2; url=login.php"); // Redirects to login after 2 seconds
            } else {
                $message = "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close(); // Close statement
        }
        $check_email_query->close(); // Close email check query
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Brew & Co.</title>
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
            <h2>Register at Brew & Co.</h2>
            
            <!-- Display the message here -->
            <?php if (!empty($message)) echo $message; ?>

            <br><form method="POST">
                <label for="email">Email <span style="color: red;">*</span></label><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                
                <label for="password">Password <span style="color: red;">*</span></label><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                
                <label for="confirm_password">Confirm Password <span style="color: red;">*</span></label><br>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
                
                <button type="submit">Register</button><br><br>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>