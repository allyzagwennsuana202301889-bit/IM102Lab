<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();
requireAdmin();

$message = "";

// Handle role toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_role'])) {
    $userId = (int)$_POST['user_id'];
    $newRole = $_POST['new_role'];

    // Prevent admin from changing their own role
    if ($userId === (int)$_SESSION['user_id']) {
        $message = "<p style='color:red;'>You cannot change your own role.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $userId);
        if ($stmt->execute()) {
            $message = "<p style='color:green;'>User role updated successfully.</p>";
        } else {
            $message = "<p style='color:red;'>Error updating role: " . $conn->error . "</p>";
        }
    }
}

// Handle delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = (int)$_POST['user_id'];

    // Prevent admin from deleting themselves
    if ($userId === (int)$_SESSION['user_id']) {
        $message = "<p style='color:red;'>You cannot delete your own account.</p>";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $message = "<p style='color:green;'>User deleted successfully.</p>";
        } else {
            $message = "<p style='color:red;'>Error deleting user: " . $conn->error . "</p>";
        }
    }
}

// Get all users
$result = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
$users = [];
$totalUsers = 0;
$adminCount = 0;
$staffCount = 0;

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
    $totalUsers++;
    if ($row['role'] === 'admin') {
        $adminCount++;
    } else {
        $staffCount++;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        .user-cards {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .user-card {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            min-width: 180px;
            text-align: center;
            flex: 1;
        }

        .user-card h3 {
            margin: 0 0 10px 0;
            color: #3B2F2F;
            font-size: 0.9em;
        }

        .user-card h2 {
            margin: 0;
            color: #6F4E37;
            font-size: 2em;
        }

        .role-admin-badge {
            background: #f44336;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .role-staff-badge {
            background: #b38c63;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .actions-form {
            display: inline;
        }

        .btn-toggle {
            padding: 6px 14px;
            background: #6F4E37;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            transition: background 0.2s;
        }

        .btn-toggle:hover {
            background: #5a3f2d;
        }

        .btn-delete-user {
            padding: 6px 14px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            transition: background 0.2s;
        }

        .btn-delete-user:hover {
            background: #d32f2f;
        }

        .current-user {
            background: #fff3cd !important;
        }

        .current-user td {
            font-weight: bold;
        }

        .user-table th {
            background: #967259;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .user-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }

        .user-table tr:hover {
            background: #f5f5f5;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            margin-top: 20px;
        }

        .page-title {
            text-align: center;
            color: #3B2F2F;
        }

        .message {
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <?php require_once 'navbar.php'; ?>

    <div class="container">
        <h1 class="page-title">User Management</h1>

        <div class="message"><?= $message; ?></div>

        <div class="user-cards">
            <div class="user-card">
                <h3>Total Users</h3>
                <h2><?= $totalUsers ?></h2>
            </div>
            <div class="user-card">
                <h3>Admins</h3>
                <h2><?= $adminCount ?></h2>
            </div>
            <div class="user-card">
                <h3>Staff</h3>
                <h2><?= $staffCount ?></h2>
            </div>
        </div>

        <table class="user-table">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr class="<?= ($user['id'] == $_SESSION['user_id']) ? 'current-user' : '' ?>">
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="role-<?= $user['role'] ?>-badge">
                            <?= htmlspecialchars($user['role']) ?>
                        </span>
                    </td>
                    <td><?= $user['created_at'] ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" class="actions-form" style="display: inline; margin-right: 5px;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="new_role" value="<?= $user['role'] === 'admin' ? 'staff' : 'admin' ?>">
                                <button type="submit" name="toggle_role" class="btn-toggle">
                                    Make <?= $user['role'] === 'admin' ? 'Staff' : 'Admin' ?>
                                </button>
                            </form>
                            <form method="POST" class="actions-form" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete user "<?= htmlspecialchars($user['username']) ?>"? This action cannot be undone.');">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="delete_user" class="btn-delete-user">Delete</button>
                            </form>
                        <?php else: ?>
                            <em>(You)</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>

</html>