<?php
require_once 'config.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';


    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    } elseif (strlen($username) > 50) {
        $errors[] = "Username cannot exceed 50 characters.";
    }

 
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

 
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }


    if (empty($confirmPassword)) {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }


    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id FROM users
             WHERE username = :username
             OR email = :email"
        );

        $stmt->execute([
            'username' => $username,
            'email' => $email
        ]);

        if ($stmt->fetch()) {

            $checkUser = $pdo->prepare(
                "SELECT id FROM users WHERE username = :username"
            );
            $checkUser->execute(['username' => $username]);

            if ($checkUser->fetch()) {
                $errors[] = "That username is already taken.";
            }

            $checkEmail = $pdo->prepare(
                "SELECT id FROM users WHERE email = :email"
            );
            $checkEmail->execute(['email' => $email]);

            if ($checkEmail->fetch()) {
                $errors[] = "That email address is already registered.";
            }
        }
    }

    if (empty($errors)) {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO users
            (username, email, password_hash)
            VALUES
            (:username, :email, :password_hash)"
        );

        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash
        ]);

        $success = "Registration successful!";
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
<div class ="container">
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
        <?= htmlspecialchars($success) ?>
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

    <button type="submit">Register</button>

</form>
</div>
</body>
</html>