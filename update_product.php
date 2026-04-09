<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];

// check if new image is uploaded
if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){

    $image = basename($_FILES['image']['name']);
    $path = "prlnxdb/uploads/" . $image;

    move_uploaded_file($_FILES['image']['tmp_name'], $path);

    $conn->query("UPDATE products 
        SET name='$name', price='$price', image='$image' 
        WHERE id=$id");

} else {
    // no new image
    $conn->query("UPDATE products 
        SET name='$name', price='$price' 
        WHERE id=$id");
}

header('Location: admin.php');
?>
