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
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 450px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .warning { color: #f44336; font-size: 1.1em; margin: 20px 0; }
        .name { font-size: 1.3em; font-weight: bold; color: #333; }
        .details { color: #666; margin: 10px 0; }
        .btn-delete { padding: 12px 30px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .btn-delete:hover { background: #d32f2f; }
        .btn-cancel { display: inline-block; padding: 12px 30px; background: #9e9e9e; color: white; text-decoration: none; border-radius: 4px; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Product</h1>
        
        <p>Are you sure you want to delete:</p>
        <p class="name">Product Name: <?= htmlspecialchars($product['product_name']) ?></p>
        <p class="desc">Description: <?= htmlspecialchars($product['description']) ?></p>
        <p class="prze">Price: <?= $product['price'] ?>
        <p class="stck">Stock: <?= $product['stock'] ?>
        <p class="category">Category: <?= htmlspecialchars($product['category_name']) ?></p>
        <p class="supplier">Supplier: <?= htmlspecialchars($product['supplier_name']) ?></p>
        <p class="warning">This action cannot be undone.</p>
        
        <form method="POST" style="display: inline;">
            <button type="submit" class="btn-delete">Yes, Delete</button>
        </form>
        <a href="index.php" class="btn-cancel">Cancel</a>
    </div>
</body>
</html>