<?php
session_start();

// Include your configuration file or any necessary dependencies here
require 'config.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: Auth/login.php");
    exit;
}

// Fetch products from the database
try {
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CERTAMEN STORE</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #FFFF00; /* Yellow background */
        color: #ffffff;
        position: relative;
    }

    .bg-image {
        background-color: #FFFF00; /* Ensure background color is yellow */
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
        background-color: #000000; /* Black background for navbar */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand,
    .nav-link {
        color: #FFFFFF !important; /* White color for navbar text and icons */
    }

    .nav-link:hover {
        color: #CCCCCC !important; /* Slightly lighter color on hover */
    }

    .container {
        padding-top: 20px;
        z-index: 1;
        position: relative;
        background-color: #000000; /* Black background for container */
        border-radius: 10px;
        padding: 20px;
        color: #FFFFFF; /* White text color inside container */
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, border 0.2s;
        background-color: #000000; /* Black background for cards */
        color: #FFFFFF; /* White text color inside cards */
        margin-bottom: 20px;
        border: 2px solid #CCCCCC; /* Default border color for the card */
    }

    .card.selected-product {
        border: 3px solid green; /* Green border for selected product */
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        color: #FFFFFF;
        font-weight: bold;
    }

    .card-text {
        color: #CCCCCC;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    /* Style for the icons in the navbar */
    .navbar-nav .nav-item {
        margin: 0 10px; /* Adjust spacing between icons */
    }

    /* Style for the "View Cart" button */
    .btn-view-cart {
        background-color: #800000; /* Maroon background color */
        border-color: #800000; /* Maroon border color */
        color: #ffffff; /* White text color */
    }

    .btn-view-cart:hover {
        background-color: #600000; /* Darker maroon for hover effect */
        border-color: #600000; /* Darker maroon for hover effect */
        color: #ffffff; /* Ensure text color remains white on hover */
    }

    /* Media query to handle responsive layout */
    @media (min-width: 576px) {
        .col-md-3 {
            max-width: 25%; /* Display 4 products per row */
        }
    }
</style>
</head>
<body>
<div class="bg-image"></div>

<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">CERTAMEN STORE</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#"><i class="fas fa-home"></i></a>
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
                <li class="nav-item">
                    <a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Our Products</h1>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 mb-4"> <!-- Display 4 products per row -->
                <div class="card">
                    <img src="image/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="card-text">RP <?php echo number_format($product['price'], 0, ',', '.'); ?></p> <!-- RP in front -->
                        <a href="Order/cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="mt-4 text-center">
        <a href="Order/cart.php" class="btn btn-view-cart">View Cart</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // JavaScript to handle the card selection
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.card').forEach(c => c.classList.remove('selected-product'));
            card.classList.add('selected-product');
        });
    });
</script>
</body>
</html>
