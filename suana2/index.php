<?php
require_once 'config.php';

$productResult = $conn->query("
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
    ORDER BY p.product_id ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
    <link rel ="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Inventory System</h1>

        <table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Supplier</th>
    </tr>

    <?php while ($row = $productResult->fetch_assoc()): ?>
    <tr>
        <td><?= $row['product_id'] ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= $row['price'] ?></td>
        <td><?= $row['stock'] ?></td>
        <td><?= htmlspecialchars($row['category_name']) ?></td>
        <td><?= htmlspecialchars($row['supplier_name']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>


    </div>
    


</body>
</html>