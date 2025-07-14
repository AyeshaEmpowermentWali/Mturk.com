<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'requester') {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $payment = $_POST['payment'];
    $deadline = $_POST['deadline'];
    $stmt = $conn->prepare("INSERT INTO tasks (requester_id, title, description, category, payment, deadline) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $description, $category, $payment, $deadline]);
    echo "<script>window.location.href='marketplace.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Task - MicroTasker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Post a Task</h2>
        <form method="POST">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="data_entry">Data Entry</option>
                    <option value="survey">Survey</option>
                    <option value="transcription">Transcription</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="payment">Payment ($)</label>
                <input type="number" id="payment" name="payment" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="datetime-local" id="deadline" name="deadline" required>
            </div>
            <button type="submit">Post Task</button>
        </form>
    </div>
</body>
</html>
