<?php

$conn = mysqli_connect("localhost", "root", "", "todolist");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>