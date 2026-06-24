<?php
require_once 'config.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'staff'; // Default to staff if not selected

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    } elseif (strlen($username) > 50) {
        $errors[] = "Username cannot exceed 50 characters.";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Validate role
    if (!in_array($role, ['admin', 'staff'])) {
        $errors[] = "Invalid role selected.";
    }

    // Check for existing username/email
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->fetch_assoc()) {
            $checkUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $checkUser->bind_param("s", $username);
            $checkUser->execute();
            $userResult = $checkUser->get_result();
            if ($userResult->fetch_assoc()) {
                $errors[] = "That username is already taken.";
            }

            $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $emailResult = $checkEmail->get_result();
            if ($emailResult->fetch_assoc()) {
                $errors[] = "That email address is already registered.";
            }
        }
    }

    // Insert user
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $username, $email, $passwordHash, $role);
        $stmt->execute();

        $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <h2>Create Account</h2>

        <?php if (!empty($errors)): ?>
            <div style="color:red;">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="color:green;">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label>Username:</label><br>
            <input type="text" name="username"
                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            <br><br>

            <label>Email:</label><br>
            <input type="email" name="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <br><br>

            <label>Password:</label><br>
            <input type="password" name="password">
            <br><br>

            <label>Confirm Password:</label><br>
            <input type="password" name="confirm_password">
            <br><br>

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="staff" <?= (!isset($_POST['role']) || $_POST['role'] === 'staff') ? 'selected' : '' ?>>Staff</option>
                    <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <br><br>

            <button type="submit">Register</button>
            <button type="button" class="logs" onclick="window.location.href='login.php'">Log In</button>

        </form>
    </div>
</body>

</html>