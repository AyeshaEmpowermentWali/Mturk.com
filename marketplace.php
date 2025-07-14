<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$category = $_GET['category'] ?? '';
$query = "SELECT tasks.*, users.username FROM tasks JOIN users ON tasks.requester_id = users.id WHERE status = 'open'";
if ($category) {
    $query .= " AND category = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$category]);
} else {
    $stmt = $conn->query($query);
}
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Marketplace - MicroTasker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .filters { margin-bottom: 20px; }
        .filters select { padding: 10px; border-radius: 5px; }
        .tasks { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .task-card { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .task-card h3 { margin: 0 0 10px; }
        .task-card p { margin: 5px 0; }
        .view-task { color: #007bff; text-decoration: none; }
        .view-task:hover { text-decoration: underline; }
        .post-task-btn { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .post-task-btn:hover { background: #218838; }
        @media (max-width: 600px) { .tasks { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <h1>Task Marketplace</h1>
        <p>Browse and post tasks</p>
    </header>
    <div class="container">
        <?php if ($_SESSION['role'] == 'requester'): ?>
            <button class="post-task-btn" onclick="window.location.href='post_task.php'">Post a Task</button>
        <?php endif; ?>
        <div class="filters">
            <label for="category">Filter by Category:</label>
            <select id="category" onchange="window.location.href='marketplace.php?category='+this.value">
                <option value="">All</option>
                <option value="data_entry" <?php echo $category == 'data_entry' ? 'selected' : ''; ?>>Data Entry</option>
                <option value="survey" <?php echo $category == 'survey' ? 'selected' : ''; ?>>Survey</option>
                <option value="transcription" <?php echo $category == 'transcription' ? 'selected' : ''; ?>>Transcription</option>
                <option value="other" <?php echo $category == 'other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="tasks">
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                    <p>Posted by: <?php echo htmlspecialchars($task['username']); ?></p>
                    <p>Category: <?php echo htmlspecialchars($task['category']); ?></p>
                    <p>Payment: $<?php echo htmlspecialchars($task['payment']); ?></p>
                    <p>Deadline: <?php echo htmlspecialchars($task['deadline']); ?></p>
                    <a href="task_details.php?id=<?php echo $task['id']; ?>" class="view-task">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
