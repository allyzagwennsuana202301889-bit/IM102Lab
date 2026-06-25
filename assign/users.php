<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();
requireAdmin();

$message = "";

// Handle edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $userId = (int)$_POST['user_id'];
    $newUsername = trim($_POST['username']);
    $newRole = $_POST['role'];
    $newPassword = $_POST['password'];

    if ($userId === (int)$_SESSION['user_id'] && $newRole !== 'admin') {
        $message = "<p style='color:red;'>You cannot demote yourself from admin.</p>";
    } elseif (empty($newUsername)) {
        $message = "<p style='color:red;'>Username cannot be empty.</p>";
    } elseif (!in_array($newRole, ['admin', 'staff'])) {
        $message = "<p style='color:red;'>Invalid role selected.</p>";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check->bind_param("si", $newUsername, $userId);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->fetch_assoc()) {
            $message = "<p style='color:red;'>Username already taken.</p>";
        } else {
            if (!empty($newPassword)) {
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username = ?, role = ?, password_hash = ? WHERE id = ?");
                $stmt->bind_param("sssi", $newUsername, $newRole, $passwordHash, $userId);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
                $stmt->bind_param("ssi", $newUsername, $newRole, $userId);
            }

            if ($stmt->execute()) {
                $message = "<p style='color:green;'>User updated successfully.</p>";
            } else {
                $message = "<p style='color:red;'>Error updating user: " . $conn->error . "</p>";
            }
        }
    }
}

// Handle delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = (int)$_POST['user_id'];

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

// Get all users with product count
$result = $conn->query("
    SELECT 
        u.id, 
        u.username, 
        u.email, 
        u.role, 
        u.created_at,
        COUNT(p.product_id) AS product_count
    FROM users u
    LEFT JOIN products p ON u.id = p.added_by
    GROUP BY u.id, u.username, u.email, u.role, u.created_at
    ORDER BY u.id ASC
");

$users = [];
$totalUsers = 0;
$adminCount = 0;
$staffCount = 0;
$totalProductsAdded = 0;

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
    $totalUsers++;
    $totalProductsAdded += $row['product_count'];
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
            min-width: 150px;
            text-align: center;
            flex: 1;
        }

        .user-card h3 {
            margin: 0 0 10px 0;
            color: #3B2F2F;
            font-size: 0.85em;
        }

        .user-card h2 {
            margin: 0;
            color: #6F4E37;
            font-size: 1.8em;
        }

        .role-admin-badge {
            background: #f44336;
            color: white;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .role-staff-badge {
            background: #b38c63;
            color: white;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .actions-form {
            display: inline;
        }



        .btn-edit:hover {
            background: #5a3f2d;
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

        .product-count {
            font-weight: bold;
            color: #6F4E37;
        }

        .product-count-zero {
            color: #999;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .modal h2 {
            margin-top: 0;
            margin-bottom: 24px;
            color: #3B2F2F;
        }

        .modal-form-row {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            margin-bottom: 20px;
        }

        .modal-field {
            flex: 1;
        }

        .modal-field label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #3B2F2F;
            font-size: 14px;
        }

        .modal-field input,
        .modal-field select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d4c4b0;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            background: #fff;
        }

        .modal-field input:focus,
        .modal-field select:focus {
            outline: none;
            border-color: #6F4E37;
        }

        .modal-field input::placeholder {
            color: #aaa;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .modal-buttons button {
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-save {
            background: #6F4E37;
            color: white;
        }

        .btn-save:hover {
            background: #5a3f2d;
        }

        .btn-cancel-modal {
            background: #b38c63;
            color: white;
        }

        .btn-cancel-modal:hover {
            background: #9a7753;
        }


        .btn-edit,
        .btn-delete-user {
            padding: 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            transition: background 0.2s;
            width: 70px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            vertical-align: middle;
        }

        .btn-edit {
            background: #6F4E37;
            color: white;
        }

        .btn-edit:hover {
            background: #5a3f2d;
        }

        .btn-delete-user {
            background: #f44336;
            color: white;
        }

        .btn-delete-user:hover {
            background: #d32f2f;
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
            <div class="user-card">
                <h3>Products Added</h3>
                <h2><?= $totalProductsAdded ?></h2>
            </div>
        </div>

        <table class="user-table">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Products Added</th>
                <th>Joined</th>
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
                    <td class="<?= $user['product_count'] == 0 ? 'product-count-zero' : 'product-count' ?>">
                        <?= $user['product_count'] ?>
                    </td>
                    <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <button type="button" class="btn-edit" onclick="openEditModal(
                                <?= $user['id'] ?>, 
                                '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>', 
                                '<?= $user['role'] ?>'
                            )">Edit</button>
                            <form method="POST" class="actions-form" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete user &quot;<?= htmlspecialchars($user['username']) ?>&quot;? This action cannot be undone.');">
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

    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <h2>Edit User</h2>
            <form method="POST">
                <input type="hidden" name="user_id" id="editUserId">

                <div class="modal-form-row">
                    <div class="modal-field">
                        <label>Username</label>
                        <input type="text" name="username" id="editUsername" required>
                    </div>

                    <div class="modal-field">
                        <label>Role</label>
                        <select name="role" id="editRole" required>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="modal-field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="blank to keep">
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="submit" name="edit_user" class="btn-save">Save Changes</button>
                    <button type="button" class="btn-cancel-modal" onclick="closeEditModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, username, role) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editRole').value = role;
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>

</body>

</html>