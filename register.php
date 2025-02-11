<?php

// 
session_start();

require 'functions.php';

if( isset($_POST["register"]) ){

    if( registrasi($_POST) > 0 ) {
        echo "<script>
                alert('user baru berhasil ditambahkan');
              </script>";

    // Langsung set session dan arahkan ke halaman index
        $_SESSION["login"] = true;
        $_SESSION["username"] = $_POST["username"];

        header("Location: index.php");
        exit;
        
    } else {
        echo mysqli_error($conn);
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
                <i class='bx bxs-lock-alt' ></i>
            </div>
            <div class="input-box">
                <input type="password" name="password2" placeholder="Konfirmasi Password" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>

            <!-- <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot password</a>
            </div> -->

            <button type="submit" name="register" class="btn">Registrasi</button>

            <div class="register-link">
                <p>Sudah memiliki akun? 
                    <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>