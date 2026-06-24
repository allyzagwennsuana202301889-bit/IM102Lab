<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "
SELECT
    p.product_id,
    p.product_name,
    p.description,
    p.price,
    p.stock,
    c.category_name,
    s.supplier_name
FROM products p
JOIN categories c
    ON p.category_id = c.category_id
JOIN suppliers s
    ON p.supplier_id = s.supplier_id
WHERE 1=1
";

if ($search != '') {
    $search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (
        p.product_name LIKE '%$search%'
        OR p.description LIKE '%$search%'
    )";
}

if ($category != '') {
    $category = (int)$category;
    $sql .= " AND p.category_id = $category";
}

$sql .= " ORDER BY p.product_id ASC";

$result = mysqli_query($conn, $sql);

$totalProducts = 0;
$totalStock = 0;
$totalValue = 0;
$lowStock = 0;
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
    $totalProducts++;
    $totalStock += $row['stock'];
    $totalValue += $row['stock'] * $row['price'];
    if ($row['stock'] < 20) {
        $lowStock++;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="style2.css">
</head>

<body>

    <?php require_once 'navbar.php'; ?>

    <div class="container">

        <div class="cards">
            <div class="card">
                <h3>Total Products</h3>
                <h2><?= $totalProducts ?></h2>
            </div>
            <div class="card">
                <h3>Total Stock</h3>
                <h2><?= $totalStock ?></h2>
            </div>
            <div class="card">
                <h3>Total Inventory Value</h3>
                <h2>₱<?= number_format($totalValue, 2) ?></h2>
            </div>
            <div class="card">
                <h3>Low Stock Items</h3>
                <h2><?= $lowStock ?></h2>
            </div>
        </div>



        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Supplier</th>
                <?php if (isAdmin()): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>

            <?php foreach ($products as $row): ?>
                <tr class="<?= ($row['stock'] < 20) ? 'low-stock' : '' ?>">
                    <td><?= $row['product_id'] ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>₱<?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                    <td><?= htmlspecialchars($row['supplier_name']) ?></td>

                    <?php if (isAdmin()): ?>
                        <td>
                            <div class="actions">
                                <a href="edit.php?id=<?= $row['product_id'] ?>"><button class="edit">Edit</button></a>
                                <a href="delete.php?id=<?= $row['product_id'] ?>" onclick="return confirm('Delete this product?')"><button class="del">Delete</button></a>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

        </table>

    </div>
</body>

</html>