<?php
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);

// Handle the actual delete (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM products WHERE product_id = $id");
    header('Location: index.php');
    exit;
}

// Show confirmation (GET)
$result = $conn->query("SELECT
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
WHERE p.product_id = $id
");
$product = $result->fetch_assoc();

if (!$product) {
    die("Not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Product</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="container">
        <h1>Delete Product</h1>
        
        <p>Are you sure you want to delete:</p>
        <p class="name"><?= htmlspecialchars($product['product_name']) ?></p>
        <p class="desc"><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
        <p class="prze"><strong>Price:</strong> <?= $product['price'] ?></p>
        <p class="stck"><strong>Stock:</strong> <?= $product['stock'] ?></p>
        <p class="category"><strong>Category: </strong> <?= htmlspecialchars($product['category_name']) ?></p>
        <p class="supplier"><strong> Supplier: </strong> <?= htmlspecialchars($product['supplier_name']) ?></p>
        <p class="warning">This action cannot be undone.</p>
        
        <form method="POST" style="display: inline;">
            <button type="submit" class="btn-delete">Yes, Delete</button>
        </form>
        <a href="index.php" class="btn-cancel">Cancel</a>
    </div>
</body>
</html>