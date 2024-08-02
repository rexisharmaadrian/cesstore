<?php
require '../config.php';

$success = ''; // Variabel untuk menyimpan pesan sukses
$error = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validasi email yang sudah ada
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $error = "Email is already registered."; // Set pesan error
    } else {
        // Insert user ke dalam database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $password]);

        $success = "User registered successfully. Redirecting to login..."; // Set pesan sukses
        // Redirect setelah beberapa detik
        echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #FFFF00; /* Latar belakang kuning */
            margin: 0; /* Menghapus margin default dari body */
        }

        .card {
            max-width: 500px; /* Maksimal lebar kartu */
            margin: 50px auto; /* Pusatkan kartu secara vertikal dan horizontal */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #800000; /* Latar belakang maroon */
            color: #ffffff; /* Teks putih */
        }

        .card-header {
            background-color: #000000; /* Latar belakang hitam untuk header */
            border-radius: 10px 10px 0 0;
            color: #ffffff; /* Teks putih */
            text-align: center;
            padding: 10px;
        }

        .card-title {
            margin-top: 10px;
            font-size: 24px;
            color: #ffffff; /* Teks putih untuk judul */
        }

        .form-group label {
            color: #ffffff; /* Teks label menjadi putih */
        }

        .form-group input {
            background-color: #ffffff; /* Latar belakang input */
            color: #000000; /* Teks input menjadi hitam */
        }

        .btn-primary {
            background-color: #000000; /* Tombol menjadi hitam */
            border: none;
            color: #ffffff; /* Teks tombol putih */
        }

        .btn-primary:hover {
            background-color: #333333; /* Warna tombol saat hover */
        }

        .text-center {
            color: #ffffff; /* Teks di bagian text-center menjadi putih */
        }

        .text-center a {
            color: #007bff; /* Teks link menjadi biru */
        }

        .text-center a:hover {
            text-decoration: underline; /* Garis bawah saat hover */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-4">
                    <header class="card-header">
                        <h1 class="card-title">
                            <i class="fas fa-smile"></i> Register
                        </h1>
                    </header>
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
