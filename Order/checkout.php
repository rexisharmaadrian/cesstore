<?php
require '../config.php';
session_start();

// Inisialisasi variabel $cart_items dengan array kosong sebagai default
$cart_items = [];

// Pastikan user_id ada di session
if (isset($_SESSION['user_id'])) {
    // Retrieve cart items for the current user
    $sql = "SELECT products.name, products.price, cart.quantity FROM cart 
            JOIN products ON cart.product_id = products.id 
            WHERE cart.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll();
} else {
    echo "User not logged in.";
    exit(); // Keluar dari script jika pengguna tidak login
}
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
            background-color: #FFFF00; /* Yellow background */
            font-family: Arial, sans-serif;
            color: #000000;
        }

        .container {
            padding-top: 50px;
            max-width: 800px;
            margin: 0 auto;
            background-color: #800000; /* Maroon background for container */
            color: #FFFFFF; /* White text color for contrast with maroon background */
            border-radius: 10px;
            padding: 20px;
        }

        .table {
            background-color: #FFFFFF; /* White background for table */
            color: #000000; /* Black text color for contrast with white background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow effect */
            border-radius: 8px;
            width: 100%;
            margin-bottom: 20px;
        }

        .table thead {
            background-color: #000000; /* Black background for table header */
            color: #FFFFFF; /* White text color for table header */
        }

        .table tbody td {
            color: #000000; /* Black text color for table body */
        }

        .btn-confirm {
            margin-top: 20px;
            background-color: #28a745; /* Green color for confirm button */
            border-color: #218838; /* Darker green border */
            color: #FFFFFF; /* White text color */
        }

        .btn-confirm:hover {
            background-color: #218838; /* Darker green on hover */
            border-color: #1e7e34; /* Even darker green border on hover */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Checkout</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>RP <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>RP <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No items in cart</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <h3>Total: RP <?php
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        echo number_format($total, 2, ',', '.'); ?></h3>
        <a href="confirm_checkout.php" class="btn btn-confirm">Confirm Purchase</a>
        <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
    </div>
</body>
</html>
