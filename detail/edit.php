<?php
session_start();
require 'koneksi.php';

if (!isset($_GET['subtask_id'])) {
    header("Location: subtasks.php");
    exit;
}

$subtask_id = $_GET['subtask_id'];

// Ambil data subtask yang akan diedit
$query = "SELECT * FROM subtasks WHERE subtask_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $subtask_id);
$stmt->execute();
$result = $stmt->get_result();
$subtask_data = $result->fetch_assoc();

// Redirect ke subtasks.php kalau data subtask nggak ditemukan
if (!$subtask_data) {
    header("Location: /todolist/subtasks.php");
    exit;
}

// Set URL kembali
$back_url = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "subtasks.php") !== false 
    ? $_SERVER['HTTP_REFERER'] 
    : "/todolist/subtasks.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subtask_label = $_POST['subtask'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $update_query = "UPDATE subtasks SET subtask_label = ?, priority = ?, deadline = ? WHERE subtask_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $subtask_label, $priority, $deadline, $subtask_id);
    $stmt->execute();

    // Redirect setelah update
    header("Location: /todolist/subtasks.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subtask</title>
    <link rel="stylesheet" href="cok.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">
                <a href="<?= htmlspecialchars($back_url) ?>" class="back-btn"><i class='bx bx-chevron-left'></i></a>
                <h1>Edit Subtask</h1>
            </div>
            <p class="description">Edit your subtask details</p>
        </div>

        <div class="subtask-section">
            <form action="" method="post">
                <label for="subtask"></label>
                <input type="text" id="subtask" name="subtask" value="<?= htmlspecialchars($subtask_data['subtask_label']) ?>" required>

                <label for="priority"></label>
                <select id="priority" name="priority">
                    <option value="rendah" <?= $subtask_data['priority'] == 'rendah' ? 'selected' : '' ?>>Rendah</option>
                    <option value="tinggi" <?= $subtask_data['priority'] == 'tinggi' ? 'selected' : '' ?>>Tinggi</option>
                </select>

                <label for="deadline"></label>
                <input type="date" id="deadline" name="deadline" value="<?= $subtask_data['deadline'] ?>">

                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
