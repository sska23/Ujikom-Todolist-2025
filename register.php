<?php
session_start();
require 'functions.php';

if (isset($_POST["register"])) {
    $result = registrasi($_POST);
    
    if ($result > 0) {
        echo "<script>alert('User baru berhasil ditambahkan');</script>";

        // Ambil data user dari database berdasarkan username
        global $conn;
        $username = $_POST["username"];
        $query = "SELECT id FROM users WHERE username = '$username'";
        $res = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($res);

        // Set session
        $_SESSION["login"] = true;
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $row["id"]; // Simpan user_id

        // Redirect ke halaman utama
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link rel="stylesheet" href="woi.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper">
        <form action="" method="post">
            <h1>Registrasi</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password2" placeholder="Konfirmasi Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <button type="submit" name="register" class="btn">Registrasi</button>

            <div class="register-link">
                <p>Sudah memiliki akun? 
                    <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>