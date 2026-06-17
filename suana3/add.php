<?php
require_once 'config.php';

$message = "";

// Get categories
$categories = $conn->query("SELECT category_id, category_name FROM categories");

// Get suppliers
$suppliers = $conn->query("SELECT supplier_id, supplier_name FROM suppliers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $conn->real_escape_string(trim($_POST['product_name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $category = (int) $_POST['category_id'];
    $supplier = (int) $_POST['supplier_id'];

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
                (product_name, description, price, stock, category_id, supplier_id)
                VALUES
                ('$name', '$description', $price, $stock, $category, $supplier)";

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
    <title>Add Product</title>

    <style>
        body{
            font-family: Arial;
            margin:20px;
        }

        .container{
            max-width:500px;
            margin:auto;
            background:white;
            padding:20px;
            border-radius:8px;
        }

        label{
            display:block;
            margin-top:10px;
            font-weight:bold;
        }

        input, textarea, select{
            width:100%;
            padding:10px;
            margin-top:5px;
            box-sizing:border-box;
        }

        button{
            margin-top:15px;
            padding:10px 20px;
        }

        .cancel{
            margin-left:10px;
        }
    </style>
</head>

<body>

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

        <?php while($row = $categories->fetch_assoc()) { ?>
            <option value="<?= $row['category_id']; ?>">
                <?= htmlspecialchars($row['category_name']); ?>
            </option>
        <?php } ?>

    </select>

    <label>Supplier</label>
    <select name="supplier_id" required>
        <option value="">-- Select Supplier --</option>

        <?php while($row = $suppliers->fetch_assoc()) { ?>
            <option value="<?= $row['supplier_id']; ?>">
                <?= htmlspecialchars($row['supplier_name']); ?>
            </option>
        <?php } ?>

    </select>

    <button type="submit">Add Product</button>
    <a href="index.php" class="cancel">Cancel</a>

</form>

</div>

</body>
</html>