<?php
require '../config.php';
session_start();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $cart_id = $_GET['id']; // Get cart.id instead of product_id
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?"; // Use cart.id for deletion
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cart_id, $user_id]);

    // Check if deletion was successful
    $deleted = $stmt->rowCount() > 0;

    if ($deleted) {
        echo "Product deleted from cart.";
    } else {
        echo "Failed to delete product from cart.";
    }
}

// Handle add action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id, 1]);

    echo "Product added to cart.";
}

// Handle update action
if (isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$quantity, $cart_id, $user_id]);

    echo "Cart updated.";
}

// Retrieve cart items for the current user
if (isset($_SESSION['user_id'])) {
    $sql = "SELECT cart.id, products.name, products.price, cart.quantity FROM cart 
            JOIN products ON cart.product_id = products.id 
            WHERE cart.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll();
} else {
    $cart_items = []; // If user_id is not set in session, initialize an empty array
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    background-image: url("cesbg.jpg");
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 100vh; /* Tinggi elemen body sesuai dengan tinggi viewport */
    margin: 0; /* Menghilangkan margin bawaan dari body */
    font-family: Arial, sans-serif; /* Contoh pengaturan font-family yang umum */
}

.container {
    padding-top: 50px; /* Jarak atas 50px dari container */
    max-width: 800px; /* Lebar maksimum container */
    margin: 0 auto; /* Tengahkan container di tengah layar */
}

.table {
    background-color: #ffffff; /* Latar belakang putih untuk tabel */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Bayangan tipis */
    border-radius: 8px; /* Sudut melengkung */
    width: 100%; /* Lebar tabel 100% dari container */
    margin-bottom: 20px; /* Jarak bawah 20px dari tabel */
    overflow: hidden; /* Mengatasi masalah overflow dari box-shadow */
}

.table th,
.table td {
    padding: 12px; /* Padding dalam sel header dan sel data */
    text-align: center; /* Pusatkan teks dalam sel */
}

.btn-checkout {
    margin-top: 20px; /* Jarak atas 20px dari tombol checkout */
}

.btn-primary,
.btn-secondary {
    display: inline-block; /* Atur tombol menjadi inline-block */
    padding: 0.375rem 0.75rem; /* Padding dalam tombol */
    text-align: center; /* Pusatkan teks dalam tombol */
    text-decoration: none; /* Hapus garis bawah pada tautan */
    cursor: pointer; /* Ubah kursor saat mengarah ke tombol */
    border-radius: 4px; /* Sudut melengkung pada tombol */
    color: #fff; /* Warna teks putih */
    transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; /* Animasi perubahan properti */
}

.btn-primary {
    background-color: #007bff; /* Warna latar belakang biru untuk tombol primer */
    border-color: #007bff; /* Warna border biru untuk tombol primer */
}

.btn-primary:hover {
    background-color: #0056b3; /* Warna latar belakang biru gelap saat tombol primer dihover */
    border-color: #0056b3; /* Warna border biru gelap saat tombol primer dihover */
}

.btn-secondary {
    background-color: #6c757d; /* Warna latar belakang abu-abu untuk tombol sekunder */
    border-color: #6c757d; /* Warna border abu-abu untuk tombol sekunder */
}

.btn-secondary:hover {
    background-color: #545b62; /* Warna latar belakang abu-abu gelap saat tombol sekunder dihover */
    border-color: #545b62; /* Warna border abu-abu gelap saat tombol sekunder dihover */
}

.quantity-input {
    width: 50px; /* Lebar input kuantitas */
    text-align: center; /* Pusatkan teks dalam input */
}

.btn-increase,
.btn-decrease {
    padding: 0.375rem 0.75rem; /* Padding dalam tombol peningkatan dan penurunan */
}

    </style>
</head>

<body>
    
    <div class="container mt-5">
        <h1 class="mb-4">total</h1>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?> RP</td>
                        <td>
                            <form action="cart.php" method="POST" class="d-inline">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="quantity" value="<?php echo $item['quantity'] - 1; ?>"
                                    class="btn btn-decrease btn-sm">-</button>
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>"
                                    class="quantity-input" min="1">
                                <button type="submit" name="quantity" value="<?php echo $item['quantity'] + 1; ?>"
                                    class="btn btn-increase btn-sm">+</button>
                            </form>
                        </td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> RP</td>
                        <td>
                            <a href="cart.php?action=delete&id=<?php echo $item['id']; ?>"
                                class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="checkout.php" class="btn btn-primary btn-checkout">Proceed to Checkout</a>
        <a href="../index.php" class="btn btn-secondary">Back to Products</a>
    </div>
</body>

</html>