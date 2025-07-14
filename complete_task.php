<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'worker') {
    header("Location: login.php");
    exit;
}
$task_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM task_assignments WHERE task_id = ? AND worker_id = ? AND status = 'accepted'");
$stmt->execute([$task_id, $_SESSION['user_id']]);
$assignment = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$assignment) {
    header("Location: dashboard.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submitted_work = $_POST['submitted_work'];
    $stmt = $conn->prepare("UPDATE task_assignments SET status = 'submitted', submitted_work = ? WHERE id = ?");
    $stmt->execute([$submitted_work, $assignment['id']]);
    echo "<script>window.location.href='dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Task - MicroTasker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Complete Task</h2>
        <form method="POST">
            <div class="form-group">
                <label for="submitted_work">Submit Your Work</label>
                <textarea id="submitted_work" name="submitted_work" rows="5" required></textarea>
            </div>
            <button type="submit">Submit Work</button>
        </form>
    </div>
</body>
</html>
