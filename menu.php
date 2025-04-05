<?php
session_start();
include "dbconnect.php"; // Database connection

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Validate database connection
if (!$mysqli) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch menu items
$query = "SELECT * FROM products";
$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($mysqli));
}

$menu_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $menu_items[$row['category']][] = [
        'id' => $row['product_id'],
        'name' => $row['name'],
        'price' => $row['price']
    ];
}

// Handle adding items to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate if all the POST data exists
    if (isset($_POST['product_id'], $_POST['item'], $_POST['price'], $_POST['quantity'])) {
        $product_id = intval($_POST['product_id']);
        $item = htmlspecialchars($_POST['item']);
        $price = floatval($_POST['price']);
        $quantity = intval($_POST['quantity']);

        if ($quantity > 0) {
            // Ensure cart item is stored using product_id
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'name' => $item,
                    'price' => $price,
                    'quantity' => $quantity
                ];
            }

            $_SESSION['message'] = "$item has been added to your cart!";
        }
    } else {
        // Handle missing POST data
        $_SESSION['message'] = "Failed to add item to cart. Missing information!";
    }

    // Redirect to prevent form resubmission
    header("Location: menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <style>
        .quantity-input { width: 50px; text-align: center; }
        .message {
            background-color: #c8e6c9;
            padding: 10px;
            color: #388e3c;
            margin-top: 10px;
            border: 1px solid #388e3c;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <a href="https://freeimage.host/i/3oOfE9p">
            <img src="https://iili.io/3oOfE9p.png" alt="Brew & Co. Logo" width="160" height="160">
        </a>
        <h2>Welcome to Brew & Co.</h2>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <section>
        <h1 class="menu-header">MENU</h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='message'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']); // Clear message after displaying
        }
        ?>

        <?php foreach (["Hot Beverages", "Cold Beverages"] as $category): ?>
            <table class="menu-table">
                <tr><th colspan="4"><?php echo $category; ?></th></tr>
                <?php if (isset($menu_items[$category])): ?>
                    <?php foreach ($menu_items[$category] as $product): ?>
                    <tr>
                        <td class="item-name"><?php echo htmlspecialchars($product['name']); ?></td>
                        <td class="item-price">₹<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <form action="menu.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="item" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <input type="number" name="quantity" min="1" value="1" required class="quantity-input">
                                <button type="submit">Add to cart</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No items available.</td></tr>
                <?php endif; ?>
            </table>
        <?php endforeach; ?>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Brew & Co. All rights reserved.</p>
    </footer>
</body>
</html>
