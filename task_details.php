<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$task_id = $_GET['id'];
$stmt = $conn->prepare("SELECT tasks.*, users.username FROM tasks JOIN users ON tasks.requester_id = users.id WHERE tasks.id = ?");
$stmt->execute([$task_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'worker') {
    $stmt = $conn->prepare("INSERT INTO task_assignments (task_id, worker_id, status) VALUES (?, ?, 'applied')");
    $stmt->execute([$task_id, $_SESSION['user_id']]);
    echo "<script>window.location.href='dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details - MicroTasker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        .task-details p { margin: 10px 0; }
        .apply-btn { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .apply-btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($task['title']); ?></h2>
        <div class="task-details">
            <p><strong>Posted by:</strong> <?php echo htmlspecialchars($task['username']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($task['category']); ?></p>
            <p><strong>Payment:</strong> $<?php echo htmlspecialchars($task['payment']); ?></p>
            <p><strong>Deadline:</strong> <?php echo htmlspecialchars($task['deadline']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
        </div>
        <?php if ($_SESSION['role'] == 'worker'): ?>
            <form method="POST">
                <button class="apply-btn" type="submit">Apply for Task</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
