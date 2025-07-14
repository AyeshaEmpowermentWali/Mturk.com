<?php
session_start();
require_once 'db.php';
$stmt = $conn->query("SELECT * FROM tasks WHERE status = 'open' LIMIT 4");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTasker - Earn Money with Small Tasks</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .intro { text-align: center; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .signup-options { display: flex; justify-content: center; gap: 20px; margin: 20px 0; }
        .signup-btn { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .signup-btn:hover { background: #218838; }
        .tasks { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
        .task-card { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .task-card h3 { margin: 0 0 10px; }
        .task-card p { margin: 5px 0; }
        .view-task { color: #007bff; text-decoration: none; }
        .view-task:hover { text-decoration: underline; }
        @media (max-width: 600px) { .signup-options { flex-direction: column; } }
    </style>
</head>
<body>
    <header>
        <h1>MicroTasker</h1>
        <p>Earn money by completing small tasks!</p>
    </header>
    <div class="container">
        <div class="intro">
            <h2>Welcome to MicroTasker</h2>
            <p>Join our platform to earn money by completing micro-tasks or post tasks to get work done quickly!</p>
            <div class="signup-options">
                <button class="signup-btn" onclick="window.location.href='signup.php?role=worker'">Sign Up as Worker</button>
                <button class="signup-btn" onclick="window.location.href='signup.php?role=requester'">Sign Up as Requester</button>
            </div>
        </div>
        <h2>Featured Tasks</h2>
        <div class="tasks">
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
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
