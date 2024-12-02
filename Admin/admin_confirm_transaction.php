<?php
// admin_confirm_transaction.php

require '../config.php';  // Pastikan path ke config.php sudah benar
session_start();

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Auth/login.php');
    exit();
}

// Ambil semua transaksi yang belum dikonfirmasi
$query = "SELECT t.*, u.username 
          FROM transaksi t 
          JOIN users u ON t.user_id = u.id 
          WHERE t.status = 'Menunggu Konfirmasi'";

$stmt = $pdo->prepare($query);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses konfirmasi transaksi jika tombol diklik
if (isset($_GET['id'])) {
    $transactionId = $_GET['id'];

    // Update status transaksi menjadi 'Diterima'
    $updateQuery = "UPDATE transaksi SET status = 'Diterima' WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':id', $transactionId, PDO::PARAM_INT);
    $updateStmt->execute();

    // Redirect setelah konfirmasi
    header('Location: admin_confirm_transaction.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Transaksi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #FFFF00;
            font-family: Arial, sans-serif;
            color: #000000;
        }

        .container {
            padding-top: 50px;
            max-width: 900px;
            margin: 0 auto;
            background-color: #800000;
            color: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
        }

        h1 {
            color: #FFFFFF;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .table {
            background-color: #FFFFFF;
            color: #000000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            margin-bottom: 20px;
        }

        .table thead {
            background-color: #000000;
            color: #FFFFFF;
        }

        .table tbody td {
            color: #000000;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            border-radius: 30px; /* Tombol lebih melengkung */
            padding: 12px 25px;  /* Ukuran lebih besar */
            font-size: 1.2rem;    /* Ukuran font lebih besar */
            font-weight: bold;    /* Teks lebih tebal */
            text-transform: uppercase;
            transition: all 0.3s ease;  /* Transisi halus untuk hover dan perubahan */
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);  /* Bayangan lembut untuk efek kedalaman */
        }

        .btn-success:hover {
            background-color: #218838;  /* Warna hijau lebih gelap saat hover */
            transform: scale(1.1);       /* Efek pembesaran */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Bayangan lebih tebal saat hover */
        }

        .btn-success i {
            margin-right: 8px; /* Ruang di antara ikon dan teks */
            font-size: 1.3rem;  /* Ukuran ikon sedikit lebih besar */
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            border-radius: 50%;
            padding: 10px 15px;
            font-size: 1.1rem;
            transition: background-color 0.3s, transform 0.2s;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Konfirmasi Transaksi</h1>
        
        <!-- Tampilkan tabel transaksi yang belum dikonfirmasi -->
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pemesanan</th>
                    <th>Nama Pengguna</th>
                    <th>Status</th>
                    <th>Total Pembayaran</th>
                    <th>Waktu Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada transaksi yang perlu dikonfirmasi</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                            <td>
                                <span class="badge badge-warning"><?php echo htmlspecialchars($transaction['status']); ?></span>
                            </td>
                            <td>RP <?php echo number_format($transaction['total'], 2, ',', '.'); ?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($transaction['created_at'])); ?></td>
                            <td>
                                <!-- Tombol untuk konfirmasi transaksi -->
                                <a href="admin_confirm_transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> Konfirmasi
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
    </div>
</body>
</html>
