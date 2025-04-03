<?php
session_start();
require 'functions.php';

$error = false;

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row["password"])) {
            // Set session login dan user_id
            $_SESSION["login"] = true;
            $_SESSION["user_id"] = $row["id"]; // Simpan user_id

            header("Location: index.php");
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <link rel="stylesheet" href="woi.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper">
        <form action="" method="post">
            <h1>Login</h1>
            
            <?php if ($error): ?>
                <p style="color: red; text-align: center;">Username atau password salah!</p>
            <?php endif; ?>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>

            <button type="submit" name="login" class="btn">Login</button>

            <div class="register-link">
                <p>Belum memiliki akun? 
                    <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>