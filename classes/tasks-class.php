<?php
require_once 'db-config.php';

class TaskManager {
    private $conn;
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function createTask($userId, $title, $description, $priority, $dueDate) {
        $query = "INSERT INTO tasks (user_id, title, description, priority, due_date) 
                  VALUES (:user_id, :title, :description, :priority, :due_date)";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'due_date' => $dueDate
        ]);
    }

    public function getUserTasks($userId) {
        try {
            // Validate user ID
            if (empty($userId)) {
                error_log("getUserTasks: Empty user ID provided");
                throw new Exception("User ID cannot be empty");
            }

            // Detailed query with more robust error checking
            $query = "SELECT 
                        task_id, 
                        user_id, 
                        title, 
                        description, 
                        status, 
                        priority, 
                        due_date 
                      FROM tasks 
                      WHERE user_id = :user_id 
                      ORDER BY 
                        CASE status 
                          WHEN 'pending' THEN 1 
                          WHEN 'in_progress' THEN 2 
                          WHEN 'completed' THEN 3 
                        END, 
                        due_date";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['user_id' => $userId]);
            
            // Fetch all tasks
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Log the number of tasks retrieved
            error_log("Retrieved " . count($tasks) . " tasks for user $userId");
            
            return $tasks;
        } catch(PDOException $e) {
            // Log specific database error
            error_log("Database error in getUserTasks: " . $e->getMessage());
            throw $e;
        } catch(Exception $e) {
            // Log other errors
            error_log("Error in getUserTasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateTaskStatus($taskId, $status) {
        $query = "UPDATE tasks SET status = :status WHERE task_id = :task_id";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            'status' => $status,
            'task_id' => $taskId
        ]);
    }

    public function deleteTask($taskId) {
        $query = "DELETE FROM tasks WHERE task_id = :task_id";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute(['task_id' => $taskId]);
    }
}