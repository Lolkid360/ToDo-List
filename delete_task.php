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

    $taskManager = new TaskManager();
    $result = $taskManager->deleteTask($taskId);

    if ($result) {
        $_SESSION['success'] = "Task deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete task";
    }

    header("Location: dashboard.php");
    exit();
}