<?php
session_start();
require 'config/db.php';
require 'functions/helpers.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pending';

if ($filter === 'completed') {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND status = 'completed'");
} elseif ($filter === 'pending') {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND status = 'pending'");
} else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
}

$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<?php require 'navbar.php'; ?>

<div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-bold mb-4">To-Do List</h1>
    
    <div class="mb-4">
        <a href="?filter=all" class="bg-blue-500 text-white font-bold py-2 px-4 rounded mr-2 hover:bg-blue-400">All Tasks</a>
        <a href="?filter=pending" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded mr-2 hover:bg-yellow-400">Pending Tasks</a>
        <a href="?filter=completed" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-400">Completed Tasks</a>
    </div>

    <table class="min-w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 p-2">Task</th>
                <th class="border border-gray-300 p-2">Status</th>
                <th class="border border-gray-300 p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($tasks) > 0): ?>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($task['title']) ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($task['status']) ?></td>
                        <td class="border border-gray-300 p-2">
                            <?php if ($task['status'] !== 'completed'): ?>
                                <a href="complete_task.php?id=<?= $task['id'] ?>" class="bg-blue-600 text-white font-bold py-1 px-3 rounded hover:bg-blue-500">Mark as Completed</a>
                            <?php endif; ?>
                            <a href="edit_task.php?id=<?= $task['id'] ?>" class="bg-gray-600 text-white font-bold py-1 px-3 rounded hover:bg-gray-500">Edit</a>
                            <a href="delete_task.php?id=<?= $task['id'] ?>" class="bg-red-600 text-white font-bold py-1 px-3 rounded hover:bg-red-500">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center border border-gray-300 p-2">No tasks found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
