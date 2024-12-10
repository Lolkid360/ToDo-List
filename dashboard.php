<?php
session_start();
require_once 'classes/tasks-class.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$taskManager = new TaskManager();

try {
    // Retrieve tasks for the logged-in user
    $tasks = $taskManager->getUserTasks($_SESSION['user_id']);
} catch(Exception $e) {
    // Log the error and set an error message
    error_log("Error retrieving tasks: " . $e->getMessage());
    $tasks = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            max-width: 900px;
            margin: 30px auto;
            padding: 15px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #007BFF;
            font-size: 1.5rem;
        }

        a.logout {
            display: block;
            text-align: right;
            margin-bottom: 15px;
            color: #ff4d4f;
            text-decoration: none;
        }

        a.logout:hover {
            text-decoration: underline;
        }

        button.add-task-btn {
            display: block;
            margin: 15px auto;
            background-color: #28a745;
            color: #fff;
            padding: 8px 16px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button.add-task-btn:hover {
            background-color: #218838;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            width: 100%;
            max-width: 450px;
            position: relative;
            box-sizing: border-box;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #aaa;
        }

        .close-btn:hover {
            color: #000;
        }

        .modal-content form input,
        .modal-content form textarea,
        .modal-content form select,
        .modal-content form button {
            display: block;
            width: 100%;
            margin: 8px 0;
            padding: 8px;
            font-size: 0.9rem;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .modal-content form textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-content form button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .modal-content form button:hover {
            background-color: #0056b3;
        }

        /* Task Styling */
        .task {
            background-color: #f4f4f4;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 5px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            font-size: 0.9rem;
        }

        .task h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }

        .task p {
            margin: 5px 0;
            font-size: 0.85rem;
        }

        .task-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            font-size: 0.85rem;
        }

        .task button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .task button:hover {
            background-color: #c82333;
        }

        .task select {
            padding: 4px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 0.85rem;
        }

        /* Sorting button */
        .sort-btn {
            margin: 15px;
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .sort-btn:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function toggleDetails(taskId) {
            const details = document.getElementById('details-' + taskId);
            const isVisible = details.style.display === 'block';
            details.style.display = isVisible ? 'none' : 'block';
        }

        function openModal() {
            document.getElementById('taskModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('taskModal').style.display = 'none';
        }

        // Close modal if clicking outside the modal content
        window.onclick = function(event) {
            if (event.target === document.getElementById('taskModal')) {
                closeModal();
            }
        }
    </script>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="logout.php" class="logout">Logout</a>

    <button class="add-task-btn" onclick="openModal()">Add New Task</button>

    <!-- Modal for Adding a New Task -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Add New Task</h2>
            <form action="add_task.php" method="POST">
                <input type="text" name="title" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description"></textarea>
                <select name="priority">
                    <option value="low">Low Priority</option>
                    <option value="medium" selected>Medium Priority</option>
                    <option value="high">High Priority</option>
                </select>
                <input type="date" name="due_date">
                <button type="submit">Add Task</button>
            </form>
        </div>
    </div>

    <h2>Your Tasks</h2>
    <?php if (!empty($tasks)): ?>
        <?php foreach($tasks as $task): ?>
            <div class="task">
                <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                <p><?php echo htmlspecialchars($task['description'] ?? 'No description'); ?></p>
                <p>Status: 
                    <span onclick="toggleDetails(<?php echo $task['task_id']; ?>)">Click to show details</span>
                    <div id="details-<?php echo $task['task_id']; ?>" style="display:none;">
                        <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
                        <p>Priority: <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p>Due Date: <?php echo htmlspecialchars($task['due_date'] ?? 'No due date'); ?></p>
                    </div>
                </p>

                <div class="task-actions">
                    <form action="update_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                        <select name="status">
                            <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="in_progress" <?php echo $task['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                    <form action="delete_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this task?');">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tasks found. Add a new task!</p>
    <?php endif; ?>
</body>
</html>
