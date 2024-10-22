<?php
session_start();
require 'config/db.php';
require 'functions/helpers.php'; 

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}


$title = ''; 
$error = ''; 


if (isset($_GET['id'])) {
    $taskId = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $_SESSION['user_id']]);
    $taskData = $stmt->fetch();

    if ($taskData) {
        $title = $taskData['title']; // Ganti 'task' menjadi 'title'
    } else {
        header("Location: index.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['task']); // Ganti 'task' menjadi 'title'

    $stmt = $pdo->prepare("UPDATE tasks SET title = ? WHERE id = ? AND user_id = ?"); // Ganti 'task' menjadi 'title'
    $stmt->execute([$title, $taskId, $_SESSION['user_id']]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css"> <!-- Link to your custom styles -->
</head>
<body>


<?php require 'navbar.php'; ?>

<div class="container mt-5">
    <h2>Edit Task</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="task">Judul Task:</label>
            <input type="text" class="form-control" id="task" name="task" value="<?= htmlspecialchars($title) ?>" required> <!-- Ganti label -->
        </div>
        <button type="submit" class="btn btn-primary">Update Task</button>
        <a href="index.php" class="btn btn-link">Kembali</a>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
