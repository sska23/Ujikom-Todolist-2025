<?php

$conn = mysqli_connect("localhost", "root", "", "todolist");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek apakah username sudah ada
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username yang Anda pilih sudah digunakan!');</script>";
        return false;    
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password salah');</script>";
        return false;
    } 

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database tanpa menyentuh kolom id
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


?>