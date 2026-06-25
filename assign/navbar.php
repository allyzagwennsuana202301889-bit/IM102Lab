<?php
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/auth.php';
}
require_once __DIR__ . '/config.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
?>
<nav class="navbar">
    <!-- Left: Burger + Brand -->
    <div class="nav-left">
        <button class="burger-btn" id="burgerBtn" aria-label="Toggle menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- Dropdown Menu -->
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="index.php">Home</a>
            <a href="report.php">Reports</a>
            <?php if (isAdmin()): ?>
                <a href="users.php">Users</a>
            <?php endif; ?>
            <a href="add.php">+ Add Product</a>
            <div class="menu-divider"></div>
            <a href="logout.php">Logout</a>
        </div>

        <a href="index.php" class="nav-brand">Inventory System</a>
    </div>

    <!-- Center: Search Bar -->
    <?php if (basename($_SERVER['PHP_SELF']) === 'index.php'): ?>
        <div class="nav-center">
            <form method="GET" action="index.php" class="search-form">
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
    <?php else: ?>
        <div class="nav-center-empty"></div>
    <?php endif; ?>

    <!-- Right: User Badge -->
    <div class="nav-right">
        <span class="user-badge">
            <?= htmlspecialchars(getUsername()) ?>
            <span class="role-badge <?= isAdmin() ? 'role-admin' : 'role-staff' ?>">
                <?= htmlspecialchars($_SESSION['role'] ?? 'guest') ?>
            </span>
        </span>
    </div>
</nav>

<script>
    const burgerBtn = document.getElementById('burgerBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    burgerBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('active');
        burgerBtn.classList.toggle('active');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!burgerBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('active');
            burgerBtn.classList.remove('active');
        }
    });

    // Close menu when clicking a link
    dropdownMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            dropdownMenu.classList.remove('active');
            burgerBtn.classList.remove('active');
        });
    });
</script>

<style>
    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #2c1810;
        padding: 12px 24px;
        margin: -20px -20px 20px -20px;
        color: #ece0d1;
        gap: 15px;
        position: relative;
    }

    /* Left: Burger + Brand */
    .nav-left {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-shrink: 0;
        position: relative;
    }

    .burger-btn {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        width: 30px;
        height: 24px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        z-index: 110;
    }

    .burger-btn .bar {
        width: 100%;
        height: 3px;
        background-color: #ece0d1;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .burger-btn.active .bar:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .burger-btn.active .bar:nth-child(2) {
        opacity: 0;
    }

    .burger-btn.active .bar:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -6px);
    }

    /* Dropdown Menu */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        background: #2c1810;
        border: 1px solid #6F4E37;
        border-radius: 6px;
        min-width: 180px;
        padding: 8px 0;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .dropdown-menu.active {
        display: block;
    }

    .dropdown-menu a {
        display: block;
        color: #ece0d1;
        text-decoration: none;
        padding: 10px 18px;
        transition: background 0.2s;
        font-size: 0.95em;
    }

    .dropdown-menu a:hover {
        background: #6F4E37;
    }

    .menu-divider {
        height: 1px;
        background: #6F4E37;
        margin: 6px 12px;
    }

    .nav-brand {
        color: #dbc1ac;
        font-weight: bold;
        font-size: 1.2em;
        text-decoration: none;
    }

    /* Center: Search */
    .nav-center {
        flex: 1;
        display: flex;
        justify-content: center;
        max-width: 600px;
    }

    .nav-center-empty {
        flex: 1;
    }

    .search-form {
        display: flex;
        gap: 8px;
        align-items: center;
        width: 100%;
    }

    .search-form input[type="text"] {
        padding: 6px 12px;
        border: 1px solid #6F4E37;
        border-radius: 4px;
        font-size: 0.9em;
        flex: 1;
        min-width: 120px;
        background: #fff;
    }

    .search-form select {
        padding: 6px 10px;
        border: 1px solid #6F4E37;
        border-radius: 4px;
        font-size: 0.9em;
        background: #fff;
        min-width: 100px;
    }

    .search-btn {
        padding: 6px 14px;
        background: #6F4E37;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9em;
        white-space: nowrap;
    }

    .search-btn:hover {
        background: #5a3f2d;
    }

    /* Right: User */
    .nav-right {
        flex-shrink: 0;
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

    /* Mobile */
    @media (max-width: 768px) {
        .navbar {
            padding: 12px 15px;
            gap: 10px;
        }

        .nav-brand {
            font-size: 1em;
        }

        .search-form input[type="text"] {
            min-width: 80px;
        }

        .search-form select {
            min-width: 80px;
            font-size: 0.8em;
        }

        .search-btn {
            padding: 6px 10px;
            font-size: 0.8em;
        }

        .user-badge {
            font-size: 0.8em;
        }
    }

    @media (max-width: 480px) {
        .search-form select {
            display: none;
        }
    }
</style>