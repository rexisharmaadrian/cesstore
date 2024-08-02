<?php
require '../config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Retrieve cart items for the current user
$sql = "SELECT products.name, products.price, cart.quantity FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Get user_id
$user_id = $_SESSION['user_id'];

// Insert the total into the transaksi table
$sql_insert_transaksi = "INSERT INTO transaksi (user_id, total) VALUES (?, ?)";
$stmt_insert_transaksi = $pdo->prepare($sql_insert_transaksi);
$stmt_insert_transaksi->execute([$user_id, $total]);

// Clear the cart after checkout
$sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
$stmt_clear_cart = $pdo->prepare($sql_clear_cart);
$stmt_clear_cart->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFFF00; /* Yellow background */
            font-family: Arial, sans-serif;
            color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
    max-width: 800px;
    background-color: #000000; /* Black background for container */
    color: #FFFFFF; /* White text color for black background */
    border-radius: 10px;
    padding: 20px;
    text-align: center;
}


        h1 {
            color: #FFFFFF; /* White text color */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            color: #FFFFFF; /* White text color */
            margin: 20px 0;
        }

        h3 {
            color: #FFFFFF; /* White text color */
            font-size: 1.5rem;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #800000; /* Maroon background */
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1.1rem;
            text-transform: uppercase;
            transition: background-color 0.3s, transform 0.2s;
            color: white; /* White text color */
        }

        .btn-primary:hover {
            background-color: #600000; /* Darker maroon on hover */
            transform: scale(1.05);
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-top: 1px solid #ddd;
        }

        .footer p {
            margin: 0;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Checkout Confirmed</h1>
        <p>Your order has been successfully placed.</p>
        <h3>Total: <?php echo number_format($total, 2, ',', '.'); ?> RP</h3>
        <a href="../index.php" class="btn btn-primary btn-home">Back to Home</a>
    </div>
 
</body>

</html>
