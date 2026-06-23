<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$id = (int)($_GET['id'] ?? 0);

// Load categories and suppliers
$categories = $conn->query("SELECT category_id, category_name FROM categories");
$suppliers = $conn->query("SELECT supplier_id, supplier_name FROM suppliers");

// Load the selected product
$result = $conn->query("SELECT * FROM products WHERE product_id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $conn->real_escape_string(trim($_POST['product_name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category = (int)$_POST['category_id'];
    $supplier = (int)$_POST['supplier_id'];

    if (
        empty($name) ||
        empty($description) ||
        $price <= 0 ||
        $stock < 0 ||
        empty($category) ||
        empty($supplier)
    ) {
        $message = "<p style='color:red;'>All fields are required.</p>";
    } else {

        $sql = "UPDATE products SET
                product_name='$name',
                description='$description',
                price=$price,
                stock=$stock,
                category_id=$category,
                supplier_id=$supplier
                WHERE product_id=$id";

        if ($conn->query($sql)) {
            header("Location: index.php");
            exit;
        } else {
            $message = "<p style='color:red;'>".$conn->error."</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>

<div class="container">

<h2>Edit Product #<?= $product['product_id']; ?></h2>

<?= $message; ?>

<form method="POST">

    <label>Product Name</label>
    <input
        type="text"
        name="product_name"
        value="<?= htmlspecialchars($product['product_name']); ?>"
        required>

    <label>Description</label>
    <textarea name="description" required><?= htmlspecialchars($product['description']); ?></textarea>

    <label>Price</label>
    <input
        type="number"
        name="price"
        step="0.01"
        value="<?= $product['price']; ?>"
        required>

    <label>Stock</label>
    <input
        type="number"
        name="stock"
        value="<?= $product['stock']; ?>"
        required>

    <label>Category</label>
    <select name="category_id" required>

        <?php while($row = $categories->fetch_assoc()) { ?>

            <option
                value="<?= $row['category_id']; ?>"
                <?= $row['category_id'] == $product['category_id'] ? 'selected' : ''; ?>>

                <?= htmlspecialchars($row['category_name']); ?>

            </option>

        <?php } ?>

    </select>

    <label>Supplier</label>
    <select name="supplier_id" required>

        <?php while($row = $suppliers->fetch_assoc()) { ?>

            <option
                value="<?= $row['supplier_id']; ?>"
                <?= $row['supplier_id'] == $product['supplier_id'] ? 'selected' : ''; ?>>

                <?= htmlspecialchars($row['supplier_name']); ?>

            </option>

        <?php } ?>

    </select>

    <button type="submit" class ="update">Update Product</button>
    <a href="index.php"><button class="cancel">Cancel</button></a>

</form>

</div>

</body>
</html>