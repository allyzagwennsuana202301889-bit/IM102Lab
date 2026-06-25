<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$message = "";

$categories = $conn->query("SELECT category_id, category_name FROM categories");
$suppliers = $conn->query("SELECT supplier_id, supplier_name FROM suppliers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $conn->real_escape_string(trim($_POST['product_name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $category = (int) $_POST['category_id'];
    $supplier = (int) $_POST['supplier_id'];
    $added_by = (int) $_SESSION['user_id'];

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

        $sql = "INSERT INTO products
                (product_name, description, price, stock, category_id, supplier_id, added_by)
                VALUES
                ('$name', '$description', $price, $stock, $category, $supplier, $added_by)";

        if ($conn->query($sql)) {
            header("Location: index.php");
            exit;
        } else {
            $message = "<p style='color:red;'>" . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>

    <?php require_once 'navbar.php'; ?>

    <div class="container">
        <h2>Add Product</h2>
        <?= $message; ?>

        <form method="POST">
            <label>Product Name</label>
            <input type="text" name="product_name" required>

            <label>Description</label>
            <textarea name="description" required></textarea>

            <label>Price</label>
            <input type="number" name="price" step="0.01" min="0" required>

            <label>Stock</label>
            <input type="number" name="stock" min="0" required>

            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($row = $categories->fetch_assoc()) { ?>
                    <option value="<?= $row['category_id']; ?>">
                        <?= htmlspecialchars($row['category_name']); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php while ($row = $suppliers->fetch_assoc()) { ?>
                    <option value="<?= $row['supplier_id']; ?>">
                        <?= htmlspecialchars($row['supplier_name']); ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit" class="add">Add Product</button>
            <button type="button" class="cancel" onclick="window.location.href='index.php'">Cancel</button>
        </form>
    </div>

</body>

</html>