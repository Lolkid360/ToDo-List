<?php
session_start();
require_once 'classes/auth-class.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $auth = new UserAuth();
    $user = $auth->login($username, $password);

    if ($user) {
        // Login successful
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        // Login failed
        $_SESSION['error'] = "Invalid username or password";
        header("Location: login.php");
        exit();
    }
}