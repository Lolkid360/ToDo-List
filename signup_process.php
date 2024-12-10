<?php
session_start();
require_once 'classes/auth-class.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: signup.php");
        exit();
    }

    $auth = new UserAuth();
    $result = $auth->signup($username, $email, $password);

    if ($result) {
        // Signup successful
        $_SESSION['success'] = "Account created successfully. Please login.";
        header("Location: login.php");
        exit();
    } else {
        // Signup failed
        $_SESSION['error'] = "Username or email already exists";
        header("Location: signup.php");
        exit();
    }
}