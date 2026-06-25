<?php
session_start();
require_once 'config.php';

// Login function
function login($username, $password)
{
    global $conn;

    $username = mysqli_real_escape_string($conn, $username);
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];        // Store user ID
        $_SESSION['username'] = $user['username'];   // Store username
        $_SESSION['role'] = $user['role'];         // Store role
        return true;
    }
    return false;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isStaff()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin()
{
    if (!isAdmin()) {
        http_response_code(403);
        die("Access denied: Admin only");
    }
}

function getUsername()  // ✅ ADD THIS FUNCTION
{
    return $_SESSION['username'] ?? 'Guest';
}
