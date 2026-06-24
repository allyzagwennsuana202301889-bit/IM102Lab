<?php
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/auth.php';
}
require_once __DIR__ . '/config.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
?>
<nav class="navbar">
    <div class="nav-brand">
        <a href="index.php">Inventory System</a>
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="report.php">Reports</a>
        <?php if (isAdmin()): ?>
            <a href="add.php">+ Add Product</a>
        <?php endif; ?>
    </div>

    <!-- Search/Filter Form moved here -->
    <?php if (basename($_SERVER['PHP_SELF']) === 'index.php'): ?>
        <div class="nav-search">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    $cats = mysqli_query($conn, "SELECT * FROM categories");
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        $selected = ($category == $cat['category_id']) ? "selected" : "";
                        echo "<option value='{$cat['category_id']}' $selected>{$cat['category_name']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="nav-user">
        <span class="user-badge">
            <?= htmlspecialchars(getUsername()) ?>
            <span class="role-badge <?= isAdmin() ? 'role-admin' : 'role-staff' ?>">
                <?= htmlspecialchars($_SESSION['role'] ?? 'guest') ?>
            </span>
        </span>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</nav>

<style>
    .navbar {
        display: flex;
        align-items: center;
        background: #2c1810;
        padding: 12px 24px;
        margin: -20px -20px 20px -20px;
        color: #ece0d1;
        flex-wrap: wrap;
        gap: 10px;
    }

    .nav-brand a {
        color: #dbc1ac;
        font-weight: bold;
        font-size: 1.2em;
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .nav-links a {
        color: #ece0d1;
        text-decoration: none;
        padding: 6px 14px;
        border-radius: 4px;
        transition: background 0.2s;
        font-size: 0.95em;
    }

    .nav-links a:hover {
        background: #6F4E37;
    }

    .nav-search form {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .nav-search input[type="text"] {
        padding: 6px 12px;
        border: 1px solid #6F4E37;
        border-radius: 4px;
        font-size: 0.9em;
        width: 160px;
        background: #fff;
    }

    .nav-search select {
        padding: 6px 10px;
        border: 1px solid #6F4E37;
        border-radius: 4px;
        font-size: 0.9em;
        background: #fff;
    }

    .search-btn {
        padding: 6px 14px;
        background: #6F4E37;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9em;
    }

    .search-btn:hover {
        background: #5a3f2d;
    }

    .nav-user {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9em;
    }

    .role-badge {
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.75em;
        font-weight: bold;
        text-transform: uppercase;
    }

    .role-admin {
        background: #f44336;
        color: white;
    }

    .role-staff {
        background: #b38c63;
        color: white;
    }

    .logout-link {
        color: #dbc1ac;
        text-decoration: none;
        padding: 6px 14px;
        border: 1px solid #6F4E37;
        border-radius: 4px;
        transition: all 0.2s;
        font-size: 0.9em;
    }

    .logout-link:hover {
        background: #6F4E37;
        color: white;
    }

    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav-user {
            margin-left: 0;
        }

        .nav-search {
            width: 100%;
        }

        .nav-search form {
            width: 100%;
        }

        .nav-search input[type="text"] {
            flex: 1;
        }
    }
</style>