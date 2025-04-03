<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"]; // Ambil user_id dari session

// Logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Proses insert task
if (isset($_POST["add"])) {
    $task = trim($_POST['task']);
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    if (empty($task) || empty($deadline)) {
        echo "<script>alert('Tugas belum diisi!');</script>";
    } else {
        // Insert dengan user_id
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task_label, task_status, priority, deadline) VALUES (?, ?, 'open', ?, ?)");
        $stmt->bind_param("isss", $user_id, $task, $priority, $deadline);
        $stmt->execute();

        if ($stmt) {
            header('Location: index.php');
            exit;
        }
    }
}

// Proses tampilkan task berdasarkan user_id
$q_select = "SELECT * FROM tasks WHERE user_id = ? ORDER BY priority DESC, task_id DESC";
$stmt = $conn->prepare($q_select);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$run_q_select = $stmt->get_result();

// Delete task
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $_GET['delete'], $user_id);
    $stmt->execute();
    header('Location: index.php');
    exit;
}
// Update status task
// Pastikan session sudah dimulai dan user_id tersedia
if (isset($_GET['done']) && isset($_GET['status'])) {
    $status = ($_GET['status'] == 'open') ? 'close' : 'open';
    $taskId = $_GET['done'];

    // Cek subtask sebelum ubah status
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM subtasks WHERE task_id = ? AND status = 'open'");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $stmt->bind_result($subtaskCount);
    $stmt->fetch();
    $stmt->close();

    if ($subtaskCount > 0) {
        echo "<script>alert('Masih ada $subtaskCount subtugas yang belum selesai!'); window.location.href = 'index.php';</script>";
        exit;
    }

    // Update status task
    $stmt = $conn->prepare("UPDATE tasks SET task_status = ? WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $taskId, $user_id);
    if (!$stmt->execute()) {
        echo "<script>alert('Gagal update status tugas!'); window.location.href = 'index.php';</script>";
        exit;
    }
    $stmt->close();

    // Jika status "close", pindahkan ke history
    if ($status == 'close') {
        // Pastikan kolom sesuai dengan tabel history
        $stmt = $conn->prepare("INSERT INTO task_history (task_id, user_id, task_label, priority, deadline, completed_at)
            SELECT task_id, user_id, task_label, priority, deadline, NOW() FROM tasks WHERE task_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $taskId, $user_id);

        if (!$stmt->execute()) {
            echo "<script>alert('Gagal memindahkan ke history!'); window.location.href = 'index.php';</script>";
            exit;
        }
        $stmt->close();

        // Hapus task dari tabel utama hanya jika berhasil masuk ke history
        $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $taskId, $user_id);
        if (!$stmt->execute()) {
            echo "<script>alert('Gagal menghapus tugas dari daftar utama!'); window.location.href = 'index.php';</script>";
            exit;
        }
        $stmt->close();
    }

    // Redirect setelah semua proses selesai
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}



$today = date('Y-m-d');
?>

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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="logout">

    </div>

    <div class="container">
        <div class="header">
            <div class="history-link">
                <a href="history/history.php" class="history-btn">Lihat History</a>
            </div>
            <div class="kolom">
            <div class="title">
                <i class='bx bx-planet'></i><span>To Do List</span>
            </div>
            <div class="description">
                <?= date("l, d M Y") ?>
            </div>
            </div>
            <form action="" method="post">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">
            <div class="task-section">
                <div class="card">
                    <form action="" method="post">
                        <input type="text" name="task" class="input-control" placeholder="Tambah tugas baru ...">
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
                if ($run_q_select->num_rows > 0) {
                    while ($r = $run_q_select->fetch_assoc()) {
                        $deadlineDate = strtotime($r['deadline']);
                        $overdueClass = ($r['task_status'] == 'open' && $deadlineDate < strtotime($today)) ? 'overdue' : '';
                ?>
                        <div class="task-item <?= $overdueClass ?>" onclick="redirectToDetail(<?= $r['task_id'] ?>)">
                        <div class="task-info">
                            <input type="checkbox" onclick="event.stopPropagation(); window.location.href = '?done=<?= $r['task_id'] ?>&status=<?= $r['task_status'] ?>'" <?= $r['task_status'] == 'close' ? 'checked' : '' ?>>
                            <span class="task-label"><?= htmlspecialchars($r['task_label']) ?></span>
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
                } else {
                    echo "<p>Tidak ada task yang ditemukan.</p>";
                }
                ?>
            </div>
            </div>
        </div>
    </div>
</body>
</html>