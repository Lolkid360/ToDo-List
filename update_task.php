<?php
session_start();
require_once 'classes/tasks-class.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskId = $_POST['task_id'];
    $status = $_POST['status'];

    $taskManager = new TaskManager();
    $result = $taskManager->updateTaskStatus($taskId, $status);

    if ($result) {
        $_SESSION['success'] = "Task status updated successfully";
    } else {
        $_SESSION['error'] = "Failed to update task status";
    }

    header("Location: dashboard.php");
    exit();
}