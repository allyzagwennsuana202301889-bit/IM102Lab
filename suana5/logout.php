<?php
require_once 'auth.php';

// Clear all session data
$_SESSION = [];

// Destroy the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
?>