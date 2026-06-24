<?php
session_start();

function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
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

function requireAdmin()
{
    if (!isAdmin()) {
        http_response_code(403);
        die("Access denied: Admin only");
    }
}

function getUsername()
{
    return $_SESSION['username'] ?? 'Guest';
}
