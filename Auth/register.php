<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ppkw";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = 'user'; // Default role adalah user

    // Cek apakah email sudah terdaftar
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        // Insert data ke tabel users tanpa hashing password
        $query = "INSERT INTO users (email, password, role, created_at) VALUES ('$email', '$password', '$role', NOW())";
        if (mysqli_query($conn, $query)) {
            $success = "Akun berhasil dibuat. Silakan login.";
        } else {
            $error = "Terjadi kesalahan saat membuat akun.";
        }
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
        body { background-color: #FFFF00; margin: 0; }
        .container { margin-top: 100px; }
        .card { border: none; border-radius: 10px; box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); }
        .card-header { background-color: #000000; border-radius: 10px 10px 0 0; color: #ffffff; text-align: center; }
        .card-body { padding: 20px; background-color: #800000; }
        .form-group label { color: #ffffff; }
        .form-group input { background-color: #ffffff; color: #000000; }
        .btn-primary { background-color: #000000; border: none; color: #ffffff; }
        .btn-primary:hover { background-color: #333333; }
        .text-center { color: #ffffff; }
        .text-center a { color: #007bff; }
        .text-center a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <header class="card-header">
                        <h4 class="card-title mt-2"><i class="fas fa-user-plus"></i> Register</h4>
                    </header>
                    <article class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert">
                                <?= htmlspecialchars($success); ?>
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
                                <button type="submit" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </form>
                    </article>
                    <div class="border-top card-body text-center">
                        Already have an account? <a href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
