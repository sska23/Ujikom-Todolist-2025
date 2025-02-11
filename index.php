<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

// Logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Proses insert data
if (isset($_POST["add"])) {
    $task = trim($_POST['task']);
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    if (empty($task) || empty($deadline)) {
        echo "<script>alert('Task belum diisi!');</script>";
    } else {
        // Menghindari SQL Injection dengan Prepared Statement
        $stmt = $conn->prepare("INSERT INTO tasks (task_label, task_status, priority, deadline) VALUES (?, 'open', ?, ?)");
        $stmt->bind_param("sss", $task, $priority, $deadline);
        $run_q_insert = $stmt->execute();

        if ($run_q_insert) {
            header('Location: index.php');
            exit;
        }
    }
}

// Proses show data task utama
$q_select = "SELECT * FROM tasks ORDER BY priority DESC, task_id DESC";
$run_q_select = mysqli_query($conn, $q_select);

// Delete dengan prepared statement
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header('Location: index.php');
    exit;
}

// Update status dengan prepared statement
if (isset($_GET['done']) && isset($_GET['status'])) {
    $status = ($_GET['status'] == 'open') ? 'close' : 'open';
    $stmt = $conn->prepare("UPDATE tasks SET task_status = ? WHERE task_id = ?");
    $stmt->bind_param("si", $status, $_GET['done']);
    $stmt->execute();
    header('Location: index.php');
    exit;
}

$today = date('Y-m-d');

?>

<!-- pindah halaman ke detail subtasks -->

<script>
function redirectToDetail(taskId) {
    window.location.href = "detail/subtasks.php?task_id=" + taskId;
}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="coba.css">
</head>
<body>
    <div class="logout">
        <form action="" method="post">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="header">
            <div class="title">
                <i class='bx bx-planet'></i><span>To Do List</span>
            </div>
            <div class="description">
                <?= date("l, d M Y") ?>
            </div>
        </div>

        <div class="content">
            <div class="task-section">
                <div class="card">
                    <form action="" method="post">
                        <input type="text" name="task" class="input-control" placeholder="Add new task...">
                        <button type="submit" name="add" id="hehe">Add</button>
                        <div class="deadline-priority-group">
                            <input type="date" name="deadline" class="input-control">
                            <select name="priority">
                                <option value="rendah">Rendah</option>
                                <option value="tinggi">Tinggi</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="task-list">
                <?php
                if (mysqli_num_rows($run_q_select) > 0) {
                    while ($r = mysqli_fetch_array($run_q_select)) {
                        $deadlineDate = strtotime($r['deadline']);
                        $overdueClass = ($r['task_status'] == 'open' && $deadlineDate < strtotime($today)) ? 'overdue' : '';

                ?>
                        <div class="task-item <?= $overdueClass ?>" onclick="redirectToDetail(<?= $r['task_id'] ?>)">
                        <div class="task-info">
                            <input type="checkbox" onclick="event.stopPropagation(); window.location.href = '?done=<?= $r['task_id'] ?>&status=<?= $r['task_status'] ?>'" <?= $r['task_status'] == 'close' ? 'checked' : '' ?>>
                            <span class="task-label"><?= $r['task_label'] ?></span>
                        </div>
                        <div class="task-details">
                            <span class="priority <?= $r['priority'] == 'tinggi' ? 'high' : 'low' ?>"><?= ucfirst($r['priority']) ?></span>
                            <span class="deadline"><?= date("d M Y", strtotime($r['deadline'])) ?></span>
                        </div>
                        <div class="task-actions">
                            <a href="edit.php?id=<?= $r['task_id'] ?>" class="edit" onclick="event.stopPropagation();"><i class='bx bxs-edit'></i></a>
                            <a href="?delete=<?= $r['task_id'] ?>" class="hapus" onclick="event.stopPropagation(); return confirm('Apa kamu yakin ingin menghapusnya?')"><i class='bx bxs-trash'></i></a>
                        </div>
                    </div>


                <?php
                    }
                }
                ?>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
