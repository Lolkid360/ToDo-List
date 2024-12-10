<?php
session_start();
require_once 'classes/tasks-class.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $priority = $_POST['priority'] ?? 'medium';
    $dueDate = $_POST['due_date'] ?? null;

    $taskManager = new TaskManager();
    $result = $taskManager->createTask(
        $_SESSION['user_id'], 
        $title, 
        $description, 
        $priority, 
        $dueDate
    );

    if ($result) {
        $_SESSION['success'] = "Task added successfully";
    } else {
        $_SESSION['error'] = "Failed to add task";
    }

    header("Location: dashboard.php");
    exit();
}