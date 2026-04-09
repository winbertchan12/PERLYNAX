<?php include 'db.php'; ?>
<h2>Admin Panel</h2>

<!-- Add Product -->
<form method="post" action="save_product.php" enctype="multipart/form-data">
<input name="name" placeholder="Product Name" required>
<input name="price" placeholder="Price" required>
<input type="file" name="image">
<button>Add Product</button>
</form>

<hr>

<!-- Product List -->
<h3>Manage Products</h3>
<table border="1" cellpadding="50">
<tr><th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Action</th></tr>

<?php
$result = $conn->query("SELECT * FROM products");
while($row = $result->fetch_assoc()):
?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['price'] ?></td>
<td><img src="prlnxdb/uploads/<?= $row['image'] ?>" width="150"></td>
<td>
  <a href="edit_product.php?id=<?= $row['id'] ?>">Edit</a> |
  <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
