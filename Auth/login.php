<?php
require '../config.php';
session_start();

$error = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
        exit(); // Pastikan untuk berhenti eksekusi setelah redirect
    } else {
        $error = "Invalid email or password."; // Set pesan error
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #FFFF00; /* Latar belakang kuning */
            margin: 0; /* Menghapus margin default dari body */
        }

        .container {
            margin-top: 100px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #000000; /* Latar belakang hitam */
            border-radius: 10px 10px 0 0;
            color: #ffffff; /* Teks putih */
            text-align: center;
        }

        .card-title {
            margin-top: 10px;
        }

        .card-body {
            padding: 20px;
            background-color: #800000; /* Latar belakang maroon */
        }

        .form-group label {
            color: #ffffff; /* Teks label menjadi putih */
        }

        .form-group input {
            background-color: #ffffff; /* Latar belakang input */
            color: #000000; /* Teks input menjadi hitam */
        }

        .btn-primary {
            background-color: #000000; /* Tombol login menjadi hitam */
            border: none;
            color: #ffffff; /* Teks tombol menjadi putih */
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
                <div class="card">
                    <header class="card-header">
                        <h4 class="card-title mt-2">
                            <i class="fas fa-smile"></i>
                        </h4>
                    </header>
                    <article class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                    </article>
                    <div class="border-top card-body text-center">
                        Don't have an account? <a href="register.php">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
