<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Ambil user_id dari session
$user_id = $_SESSION["user_id"];

// Query hanya untuk task_history milik user yang sedang login
$query = "SELECT * FROM task_history WHERE user_id = ? ORDER BY task_id DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Task History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <a href="../index.php" class="back-link"><i class='bx bx-chevron-left'>Back</i></a>
    <h1>Task History</h1>

    <div class="history-list">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Completed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['task_label']) ?></td>
                            <td><?= ucfirst($row['priority']) ?></td>
                            <td><?= date("d M Y H:i", strtotime($row['completed_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada task yang selesai.</p>
        <?php endif; ?>
    </div>

</body>
</html>
