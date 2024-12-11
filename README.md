# Task Master: Personal Task Management Web Application

## Project Overview

Task Master is a comprehensive web-based task management application designed to help users organize, track, and manage their daily tasks efficiently.

## Database Schema

### Tables

#### Users Table
- `user_id`: Unique identifier for each user (Auto-incrementing primary key)
- `username`: Unique username (50 character limit)
- `email`: Unique user email (100 character limit)
- `password_hash`: Securely hashed password
- `created_at`: Timestamp of user registration

#### Tasks Table
- `task_id`: Unique identifier for each task (Auto-incrementing primary key)
- `user_id`: Foreign key linking to Users table
- `title`: Task title (255 character limit)
- `description`: Detailed task description
- `status`: Current task status (pending, in_progress, completed)
- `priority`: Task priority level (low, medium, high)
- `due_date`: Optional deadline for the task
- `created_at`: Timestamp of task creation

## Database Setup

### Prerequisites
- MySQL/MariaDB database server
- Database user with appropriate permissions

### Installation Steps

1. Create the database:
   ```sql
   CREATE DATABASE task_master;
   USE task_master;
   ```

2. Run the following SQL scripts to set up tables:
   ```sql
   -- Create Users Table
   CREATE TABLE users (
       user_id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) UNIQUE NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       password_hash VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   -- Create Tasks Table
   CREATE TABLE tasks (
       task_id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT,
       title VARCHAR(255) NOT NULL,
       description TEXT,
       status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
       priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
       due_date DATE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
   );

   -- Performance Index
   CREATE INDEX idx_user_tasks ON tasks(user_id);
   ```

## Performance Considerations

- An index has been created on the `user_id` column in the tasks table to optimize query performance for user-specific task retrieval.
- Foreign key constraint with `ON DELETE CASCADE` ensures that when a user is deleted, all associated tasks are automatically removed.

## Security Features

- Unique constraints on username and email prevent duplicate registrations
- Password storage uses secure hashing (implement with a strong hashing algorithm like bcrypt)
- Enum constraints for status and priority ensure data integrity

## Future Improvements
- Add task categories
- Implement task sharing between users
- Create more granular permission levels
- Add task comments and subtasks
