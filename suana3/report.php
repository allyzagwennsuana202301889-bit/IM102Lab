<?php
require_once 'config.php';

$summaryQuery = mysqli_query($conn, "
SELECT
    COUNT(*) AS total_products,
    SUM(stock) AS total_stock,
    SUM(stock * price) AS total_value,
    SUM(CASE WHEN stock < 20 THEN 1 ELSE 0 END) AS low_stock
FROM products
");

$summary = mysqli_fetch_assoc($summaryQuery);

$categoryReport = mysqli_query($conn, "
SELECT
    c.category_name,
    COUNT(p.product_id) AS product_count,
    COALESCE(SUM(p.stock),0) AS total_stock,
    COALESCE(SUM(p.stock * p.price),0) AS total_value,
    COALESCE(AVG(p.price),0) AS average_price
FROM categories c
LEFT JOIN products p
    ON c.category_id = p.category_id
GROUP BY c.category_id, c.category_name
ORDER BY c.category_name ASC
");

$supplierReport = mysqli_query($conn, "
SELECT
    s.supplier_name,
    COUNT(p.product_id) AS product_count,
    COALESCE(SUM(p.stock),0) AS total_stock
FROM suppliers s
LEFT JOIN products p
    ON s.supplier_id = p.supplier_id
GROUP BY s.supplier_id, s.supplier_name
ORDER BY s.supplier_name ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Reports</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
            margin:0;
            padding:20px;
        }

        .container{
            max-width:1200px;
            margin:auto;
        }

        h1,h2{
            text-align:center;
        }

        .cards{
            display:flex;
            gap:20px;
            margin-bottom:30px;
            flex-wrap:wrap;
        }

        .card{
            flex:1;
            min-width:200px;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
            text-align:center;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
            margin-bottom:40px;
        }

        th{
            background:#333;
            color:white;
        }

        th,td{
            border:1px solid #ddd;
            padding:10px;
            text-align:center;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Inventory Reports</h1>
<a href="index.php"
style="
display:inline-block;
padding:10px 20px;
background:#4169E1;
color:white;
text-decoration:none;
border-radius:4px;
">
Home
</a>
    <div class="cards">

        <div class="card">
            <h3>Total Products</h3>
            <h2><?= $summary['total_products']; ?></h2>
        </div>

        <div class="card">
            <h3>Total Stock</h3>
            <h2><?= $summary['total_stock']; ?></h2>
        </div>

        <div class="card">
            <h3>Total Inventory Value</h3>
            <h2>₱<?= number_format($summary['total_value'],2); ?></h2>
        </div>

        <div class="card">
            <h3>Low Stock Items</h3>
            <h2><?= $summary['low_stock']; ?></h2>
        </div>

    </div>

   
    <h2>Per-Category Breakdown</h2>

    <table>
        <tr>
            <th>Category</th>
            <th>Product Count</th>
            <th>Total Stock</th>
            <th>Total Value</th>
            <th>Average Price</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($categoryReport)): ?>

        <tr>
            <td><?= htmlspecialchars($row['category_name']); ?></td>
            <td><?= $row['product_count']; ?></td>
            <td><?= $row['total_stock']; ?></td>
            <td>₱<?= number_format($row['total_value'],2); ?></td>
            <td>₱<?= number_format($row['average_price'],2); ?></td>
        </tr>

        <?php endwhile; ?>

    </table>

 
    <h2>Per-Supplier Breakdown</h2>

    <table>
        <tr>
            <th>Supplier</th>
            <th>Product Count</th>
            <th>Total Stock</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($supplierReport)): ?>

        <tr>
            <td><?= htmlspecialchars($row['supplier_name']); ?></td>
            <td><?= $row['product_count']; ?></td>
            <td><?= $row['total_stock']; ?></td>
        </tr>

        <?php endwhile; ?>

    </table>

</div>

</body>
</html>
```
