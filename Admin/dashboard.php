<?php
// dashboard.php

require '../config.php';
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Auth/login.php');
    exit();
}

// Ambil data produk untuk menampilkan jumlah total produk (opsional)
$query = "SELECT COUNT(*) AS total_products FROM products";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalProducts = $data['total_products'];
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// Cek apakah ada parameter status di URL
$successMessage = '';
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $successMessage = 'Transaction confirmed successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        .navbar {
            background-color: #000000;
            color: #ffffff;
        }

        .navbar a {
            color: #ffffff;
            margin-right: 15px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #800000;
            color: #ffffff;
            text-align: center;
        }

        .btn-primary {
            background-color: #000000;
            border: none;
        }

        .btn-primary:hover {
            background-color: #333333;
        }

        .alert-success {
            margin-top: 20px;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ml-auto">
        <a href="products.php" class="btn btn-primary btn-sm">Manage Products</a>
        <a href="admin_confirm_transaction.php?id=123&success=true" class="btn btn-primary btn-sm">Confirm</a>
        <a href="add_product.php" class="btn btn-success btn-sm">Add Product</a>
        <a href="../Auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        
    </div>
</nav>

    <div class="container">
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Welcome, Admin!</h4>
                    </div>
                    <div class="card-body">
                        <p>You have total <strong><?= $totalProducts; ?></strong> products listed in the store.</p>
                        <a href="products.php" class="btn btn-primary btn-block">Manage Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
