<?php
require 'config.php';

// Ambil data produk dengan pencarian
$searchQuery = '';

// Cek jika ada pencarian
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM products WHERE name LIKE :searchQuery OR description LIKE :searchQuery";
} else {
    // Jika tidak ada pencarian, tampilkan semua produk
    $query = "SELECT * FROM products";
}

$stmt = $pdo->prepare($query);
if ($searchQuery) {
    $stmt->execute(['searchQuery' => '%' . $searchQuery . '%']);
} else {
    $stmt->execute();
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ppkw store</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: ##9B59B6;
            color: ##FF69B4;
            position: relative;
        }
        .bg-image {
            background-color: #FFC0CB;
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
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
        }
        .container {
            padding-top: 20px;
            z-index: 1;
            position: relative;
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            color: #000000;
        }
        .card {
            background-color: #FFC0CB;
            color: #FFFFFF;
            margin-bottom: 20px;
            border: 2px solid #007bff;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="bg-image"></div>

<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">PPKW STORE</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="history.php"><i class="fas fa-history"></i> Riwayat Transaksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Order/cart.php"><i class="fas fa-shopping-cart"></i></a>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="Auth/logout.php"><i class="fas fa-sign-out-alt"></i></a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="Auth/login.php"><i class="fas fa-sign-in-alt"></i></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <!-- Form pencarian produk -->
    <form method="GET" class="form-inline mb-4">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search Products" value="<?= htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <h1 class="mb-4 text-center">Our Products</h1>
    <div class="row">
        <?php if (empty($products)): ?>
            <p class="text-center w-100">No products found.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?= 'admin/' . $product['image']; ?>" class="card-img-top product-img" alt="Product Image">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product['description']); ?></p>
                        <p class="card-text">RP <?= number_format($product['price'], 0, ',', '.'); ?></p>
                        <a href="Order/cart.php?action=add&id=<?= $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
