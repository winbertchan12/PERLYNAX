<?php
include 'db.php';

$name = $_POST['name'];
$price = $_POST['price'];

// image upload
$imageName = $_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];

$path = "prlnxdb/uploads/" . $imageName;

// move file
move_uploaded_file($tmp, $path);

// save to database
$conn->query("INSERT INTO products(name, price, image) 
VALUES('$name','$price','$imageName')");

header('Location: admin.php');
?>
