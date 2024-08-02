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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('cesbg.jpg'); /* Background image */
            background-size: cover; /* Cover the whole page */
            background-attachment: fixed; /* Fix the background */
            background-position: center; /* Center the background image */
            color: #333;
        }

        .container {
            margin-top: 50px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* White background with transparency */
            border-radius: 10px;
        }

        .table th, .table td {
            text-align: center; /* Center align table content */
        }

        .btn-confirm {
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4 text-center">Checkout</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo number_format($item['price'], 2, ',', '.'); ?> RP</td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?> RP</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3 class="text-center">Total: <?php
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        echo number_format($total, 2, ',', '.'); ?> RP
        </h3>
        <div class="text-center">
            <a href="confirm_checkout.php" class="btn btn-primary btn-confirm">Confirm Purchase</a>
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
        </div>
    </div>
    <div class="footer">
        &copy; <?php echo date("Y"); ?> Your Company Name. All rights reserved.
    </div>
</body>

</html>
