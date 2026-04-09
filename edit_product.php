<?php
include 'db.php';
$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>

<h2>Edit Product</h2>
<form method="post" action="update_product.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $product['id'] ?>">
<input name="name" value="<?= $product['name'] ?>">
<input name="price" value="<?= $product['price'] ?>">
<br>
<img src="prlnxdb/uploads/<?= $product['image'] ?>" width="100"><br>
<input type="file" name="image">
<button>Update</button>
</form>