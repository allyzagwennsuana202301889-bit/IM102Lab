<?php
require_once 'config.php';

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
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h1>Inventory System</h1>

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
        <h2>₱<?= number_format($totalValue,2) ?></h2>
    </div>

    <div class="card">
        <h3>Low Stock Items</h3>
        <h2><?= $lowStock ?></h2>
    </div>

</div>

<div class="choose">

<form method="GET">

    <input type="text" name="search" placeholder="Search..."  value="<?= htmlspecialchars($search) ?>">

    <select name="category">

        <option value="">All Categories</option>

        <?php

        $cats = mysqli_query($conn, "SELECT * FROM categories");

        while($cat = mysqli_fetch_assoc($cats)){

            $selected = ($category == $cat['category_id']) ? "selected" : "";

            echo "<option value='{$cat['category_id']}' $selected>
                    {$cat['category_name']}
                  </option>";
        }

        ?>

    </select>

    <button type="submit">Search</button>

</form>
<a href="report.php"
style="
display:inline-block;
padding:10px 20px;
background:#4169E1;
color:white;
text-decoration:none;
border-radius:4px;
margin-left:300px;
">
Reports
</a>
<p>
<a href="add.php"
style="
display:inline-block;
padding:10px 20px;
background:#4CAF50;
color:white;
text-decoration:none;
border-radius:4px;
">
+ Add Product
</a>
</p>

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
    <th>Action </th>
</tr>

<?php foreach($products as $row): ?>

<tr class="<?= ($row['stock'] < 20) ? 'low-stock' : '' ?>">

    <td><?= $row['product_id'] ?></td>

    <td><?= htmlspecialchars($row['product_name']) ?></td>

    <td><?= htmlspecialchars($row['description']) ?></td>

    <td>₱<?= number_format($row['price'],2) ?></td>

    <td><?= $row['stock'] ?></td>

    <td><?= htmlspecialchars($row['category_name']) ?></td>

    <td><?= htmlspecialchars($row['supplier_name']) ?></td>

       <td>
    <a href="edit.php?id=<?= $row['product_id'] ?>" 
       style="color: #2196F3; text-decoration: none; margin-right: 10px;">Edit</a>
    <a href="delete.php?id=<?= $row['product_id'] ?>" 
       style="color: #f44336; text-decoration: none;"
       onclick="return confirm('Delete this product?')">Delete</a>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</body>
</html>