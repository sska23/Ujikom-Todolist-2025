<?php

include 'koneksi.php';

// Select data yang akan di edit
$q_select = "SELECT * FROM tasks WHERE task_id = '" . $_GET['id'] . "' ";
$run_q_select = mysqli_query($conn, $q_select);
$d = mysqli_fetch_object($run_q_select);

// Proses edit data
if (isset($_POST['edit'])) {
    $task = $_POST['task'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];

    if (empty($task) || empty($deadline) || empty($priority)) {
        echo "<script>alert('Task, deadline, atau prioritas tidak boleh kosong!');</script>";
    } else {
        $q_update = "UPDATE tasks SET task_label = '$task', deadline = '$deadline', priority = '$priority' WHERE task_id = '" . $_GET['id'] . "' ";
        $run_q_update = mysqli_query($conn, $q_update);

        if ($run_q_update) {
            header('Location: index.php');
        } else {
            echo "<script>alert('Gagal memperbarui data!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit To Do Task</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="dit.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="title">
                <a href="index.php"><i class='bx bx-chevron-left'>Back</i></a>
            </div>
            <div class="description">
                <?= date("l, d M Y") ?>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <form action="" method="post">
                    <input type="text" name="task" class="input-control" placeholder="Edit task..." value="<?= $d->task_label ?>">
                    <input type="date" name="deadline" class="input-control" value="<?= $d->deadline ?>">
                    <select name="priority" class="input-control">
                        <option value="Rendah" <?= $d->priority == 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                        <option value="Tinggi" <?= $d->priority == 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
                    </select>
                    <button type="submit" name="edit">Edit Task</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
