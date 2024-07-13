<?php
require '../config.php';
session_start();

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

// Clear the cart after checkout
$sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
$stmt_clear_cart = $pdo->prepare($sql_clear_cart);
$stmt_clear_cart->execute([$_SESSION['user_id']]);
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
            background-color: #f8f9fa;
            position: relative;
        }

        .bg-image {
            background-image: url('cesbg.jpg'); /* Ganti dengan path gambar latar belakang Anda */
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .container {
            padding-top: 20px;
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.8); /* Warna latar belakang kontainer dengan transparansi */
            border-radius: 10px;
            padding: 20px;
        }

        .btn-home {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="bg-image"></div>
    <div class="container mt-5">
        <h1 class="mb-4">Checkout Confirmed</h1>
        <p>Your order has been confirmed.</p>
        <h3>Total: <?php echo number_format($total, 2, ',', '.'); ?> RP</h3>
        <a href="../index.php" class="btn btn-primary btn-home">Back to Home</a>
    </div>
</body>

</html>
