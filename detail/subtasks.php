<?php 
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

// Ambil task_id dari URL
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    // Query untuk mengambil task_label berdasarkan task_id
    $q_select_task = "SELECT task_label FROM tasks WHERE task_id = ?";
    $stmt = $conn->prepare($q_select_task);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task_data = $result->fetch_assoc();
        $task_label = $task_data['task_label'];
    } else {
        header("Location: index.php");
        exit;
    }

    // Proses insert subtask
    if (isset($_POST["add_subtask"])) {
        $subtask = trim($_POST['subtask']);
        $priority = $_POST['sub_priority'] ?? 'rendah';
        $deadline = $_POST['sub_deadline'] ?? null;
    
        if (!empty($subtask)) {
            $stmt = $conn->prepare("INSERT INTO subtasks (task_id, subtask_label, priority, deadline) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $task_id, $subtask, $priority, $deadline);
            $stmt->execute();
            header("Location: subtasks.php?task_id=$task_id");
            exit;
        } else {
            echo "<script>alert('Subtask belum diisi!');</script>";    
        }
    }

    // Proses update subtask
    if (isset($_POST['edit_subtask'])) {
        $subtask_id = $_POST['subtask_id'];
        $subtask_label = trim($_POST['subtask_label']);
        $priority = $_POST['sub_priority'];
        $deadline = $_POST['sub_deadline'];

        $stmt = $conn->prepare("UPDATE subtasks SET subtask_label = ?, priority = ?, deadline = ? WHERE subtask_id = ?");
        $stmt->bind_param("sssi", $subtask_label, $priority, $deadline, $subtask_id);
        $stmt->execute();
        header("Location: subtasks.php?task_id=$task_id");
        exit;
    }

    // Proses update status checkbox (open/close)
    if (isset($_GET['subtask_id']) && isset($_GET['status'])) {
        $subtask_id = $_GET['subtask_id'];
        $status = $_GET['status'];
        $stmt = $conn->prepare("UPDATE subtasks SET status = ? WHERE subtask_id = ?");
        $stmt->bind_param("si", $status, $subtask_id);
        $stmt->execute();
        $stmt->close();
        header("Location: subtasks.php?task_id=$task_id");
        exit;
    }

    // Proses delete subtask
    if (isset($_GET['delete_subtask'])) {
        $q_delete_subtask = "DELETE FROM subtasks WHERE subtask_id = '" . $_GET['delete_subtask'] . "'";
        mysqli_query($conn, $q_delete_subtask);
        header('Location: subtasks.php?task_id=' . $task_id);
        exit;
    }

    // Ambil subtasks berdasarkan task_id
    $q_select_subtask = "SELECT * FROM subtasks WHERE task_id = ?";
    $stmt = $conn->prepare($q_select_subtask);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $subtask_result = $stmt->get_result();
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subtasks of <?= htmlspecialchars($task_label) ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="d.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">
                <div class="back-btn">
                    <a href="../index.php" class="back-link"><i class='bx bx-chevron-left'></i></a>
                </div>
               <span><?= htmlspecialchars($task_label) ?></span>
            </div>
            <div class="description">
                <?= date("l, d M Y") ?>
            </div>
        </div>

        <div class="content">
            <div class="subtask-section">
                <form action="" method="post">
                    <input type="hidden" name="task_id" value="<?= $task_id ?>">
                    <input type="text" name="subtask" placeholder="Add new subtask..." required>
                    <input type="date" name="sub_deadline" required>
                    <select name="sub_priority">
                        <option value="rendah">Rendah</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                    <button type="submit" name="add_subtask">Add</button>
                </form>

                <div class="subtask-list">
                    <?php while ($sub = mysqli_fetch_array($subtask_result)) { ?>
                        <div class="subtask-item">
                            <input type="checkbox" id="subtask-<?= $sub['subtask_id'] ?>" class="subtask-checkbox" 
                                <?= $sub['status'] == 'close' ? 'checked' : '' ?> 
                                onclick="window.location='subtasks.php?task_id=<?= $task_id ?>&subtask_id=<?= $sub['subtask_id'] ?>&status=<?= $sub['status'] == 'close' ? 'open' : 'close' ?>'">
                            <label for="subtask-<?= $sub['subtask_id'] ?>" class="subtask-label"><?= htmlspecialchars($sub['subtask_label']) ?></label>

                            <span class="subtask-priority <?= $sub['priority'] === 'tinggi' ? 'high-priority' : 'low-priority' ?>">
                                <?= ucfirst($sub['priority']) ?>
                            </span>

                            <span class="subtask-deadline">
                                <?= date("d M Y", strtotime($sub['deadline'])) ?>
                            </span>

                            <a href="edit.php?subtask_id=<?= $sub['subtask_id'] ?>&task_id=<?= $task_id ?>" class="edit">
                                <i class='bx bxs-edit'></i>
                            </a>
                            <a href="?delete_subtask=<?= $sub['subtask_id'] ?>&task_id=<?= $task_id ?>" class="hapus" 
                                onclick="return confirm('Apa kamu yakin ingin menghapusnya?')">
                                <i class='bx bxs-trash'></i>
                            </a>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
