<?php
session_start();
include '../config.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$page = $_GET['page'] ?? 'products';
$search = $_GET['search'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - PERLYNAX</title>
<link rel="stylesheet" href="../style.css">
<style>
/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    display: flex;
    min-height: 100vh;
    background: linear-gradient(135deg, #eef2f7, #f8fafc);
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    background: #111;
    color: #fff;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #ff6600;
}

.sidebar a {
    padding: 15px 20px;
    text-decoration: none;
    color: #fff;
    transition: 0.3s;
}

.sidebar a:hover {
    background: #333;
}

/* MAIN CONTENT */
.main {
    flex-grow: 1;
    padding: 25px;
}

/* HEADER */
header {
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(10px);
    padding: 15px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

/* CARD */
.card {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    margin-top: 20px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    overflow: hidden;
    border-radius: 10px;
}

th {
    background: #111;
    color: #fff;
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

/* FORM INPUTS */
input[type=text],
input[type=number],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 8px;
    border: 1px solid #ddd;
}

/* BUTTON */
button {
    padding: 10px 15px;
    border: none;
    border-radius: 20px;
    background: linear-gradient(45deg, #ff6600, #ff8533);
    color: #fff;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.05);
}

/* LINKS */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* SEARCH BAR */
form input[type=text] {
    width: 200px;
    display: inline-block;
}

/* IMAGE */
img {
    border-radius: 6px;
}

/* RESPONSIVE */
@media(max-width:768px) {
    body {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        flex-direction: row;
        overflow-x: auto;
    }

    .sidebar a {
        flex: 1;
        text-align: center;
    }
}
</style>

</head>
<body>
<div class="sidebar">
<h2 style="text-align:center;">PERLYNAX Admin</h2>
<a href="?page=products">Products</a>
<a href="?page=orders">Orders</a>
<a href="?page=users">Users</a>
<a href="logout.php">Logout</a>
</div>
<div class="main">
<header><h1>Dashboard</h1></header>

<?php
// ------------------- PRODUCTS MANAGEMENT -------------------
if($page === 'products') {
    echo "<div class='card'>";
    echo "<h2>Products</h2>";
    echo "<a href='?page=add_product'>Add Product</a> | ";
    echo "<form method='get' style='display:inline-block;'><input type='hidden' name='page' value='products'>
    <input type='text' name='search' placeholder='Search products...' value='".htmlspecialchars($search)."'>
    <button type='submit'>Search</button></form>";

    $sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
    $res = $conn->query($sql);
    echo "<table><tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Actions</th></tr>";
    while($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['id']}</td>
        <td><img src='uploads/{$row['image']}' width='50'></td>
        <td>{$row['name']}</td>
        <td>₱{$row['price']}</td>
        <td>
        <a href='?page=edit_product&id={$row['id']}'>Edit</a> |
        <a href='?page=delete_product&id={$row['id']}' onclick='return confirm(\"Delete product?\")'>Delete</a>
        </td>
        </tr>";
    }
    echo "</table>";
    echo "</div>";


// ------------------- ADD PRODUCT WITH IMAGE UPLOAD -------------------
} elseif($page === 'add_product') {
    if(isset($_POST['add'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $desc = $conn->real_escape_string($_POST['description']);
        $price = $_POST['price'];

        // Handle file upload
        $image = 'default.png';
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
           // $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads.$image");
        }

        $conn->query("INSERT INTO products (name,description,price,image) VALUES ('$name','$desc',$price,'$image')");
        echo "<p style='color:green;'>Product added!</p>";
    }
    echo "<h2>Add Product</h2>
    <form method='post' enctype='multipart/form-data'>
    Name: <input type='text' name='name' required><br>
    Description: <textarea name='description'></textarea><br>
    Price: <input type='number' step='0.01' name='price' required><br>
    Image: <input type='file' name='image'><br>
    <button type='submit' name='add'>Add Product</button>
    </form>";

// ------------------- EDIT PRODUCT -------------------
} elseif($page === 'edit_product') {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM products WHERE id=$id");
    $prod = $res->fetch_assoc();

    if(isset($_POST['update'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $desc = $conn->real_escape_string($_POST['description']);
        $price = $_POST['price'];
        $image = $prod['image'];
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            $image = basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'],"uploads/" . $image
);

        }
        $conn->query("UPDATE products SET name='$name',description='$desc',price=$price,image='$image' WHERE id=$id");
        echo "<p style='color:green;'>Product updated!</p>";
    }

    echo "<h2>Edit Product</h2>
    <form method='post' enctype='multipart/form-data'>
    Name: <input type='text' name='name' value='{$prod['name']}' required><br>
    Description: <textarea name='description'>{$prod['description']}</textarea><br>
    Price: <input type='number' step='0.01' name='price' value='{$prod['price']}' required><br>
    Image: <input type='file' name='image'><br>
    <img src='uploads/{$prod['image']}' width='80'><br>
    <button type='submit' name='update'>Update Product</button>
    </form>";

// ------------------- DELETE PRODUCT -------------------
} elseif($page === 'delete_product') {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: index.php?page=products");

// ------------------- ORDERS WITH DETAILS -------------------
} elseif($page === 'orders') {
    echo "<h2>Orders</h2>";
    echo "<form method='get'><input type='hidden' name='page' value='orders'>
    <input type='text' name='search' placeholder='Search orders by user...' value='".htmlspecialchars($search)."'>
    <button type='submit'>Search</button></form>";

    $sql = "SELECT orders.*, users.username FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE users.username LIKE '%$search%'
            ORDER BY orders.created_at DESC";
    $res = $conn->query($sql);
    echo "<table><tr><th>Order ID</th><th>User</th><th>Total</th><th>Date</th><th>Details</th></tr>";
    while($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['username']}</td>
        <td>₱{$row['total']}</td>
        <td>{$row['created_at']}</td>
        <td><a href='?page=order_details&id={$row['id']}'>View Items</a></td>
        </tr>";
    }
    echo "</table>";

/// ------------------- ORDER DETAILS -------------------
} elseif($page === 'order_details') {
    $id = intval($_GET['id']);
          echo"  <h2><a href='?page=orders'>❮ Back</a></h2>";
          

    // Fetch order details along with user info
    $order_res = $conn->query("
        SELECT orders.user_id, users.email, users.address, users.city, users.province
        FROM orders
        JOIN users ON orders.user_id = users.id
        WHERE orders.id = $id
        
    ");
    
    if ($order_res && $order_res->num_rows > 0) {
        $order = $order_res->fetch_assoc();
        echo "<h2>Order #$id Details</h2>";
        echo "<p><strong>User:</strong> {$order['email']}<br>
              <strong>Address:</strong> {$order['address']}, {$order['city']}, {$order['province']}</p>";
              
    } else {
        echo "<p>Order not found.</p>";
        return;
    }

    // Fetch order items
    $res = $conn->query("
        SELECT order_items.quantity, products.name, products.price 
        FROM order_items 
        JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = $id
        
    ");

    echo "<table border='1' cellpadding='5'><tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>";
    $total = 0;
    
    while($row = $res->fetch_assoc()) {
        $subtotal = $row['quantity'] * $row['price'];
        $total += $subtotal;
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['quantity']}</td>
            <td>₱{$row['price']}</td>
            <td>₱{$subtotal}</td>
            
        </tr>";
        
        
    }
    echo "<tr><td colspan='3'><strong>Total</strong></td><td>₱$total</td></tr></table>";
   


// ------------------- USERS MANAGEMENT -------------------
} elseif($page === 'users') {
    echo "<h2>Users</h2>";
    echo "<form method='get'><input type='hidden' name='page' value='users'>
    <input type='text' name='search' placeholder='Search users...' value='".htmlspecialchars($search)."'>
    <button type='submit'>Search</button></form>";

    $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
    $res = $conn->query($sql);
    echo "<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Address</th><th>City</th><th>Province</th><th>Actions</th></tr>";
    while($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['email']}</td>
        <td>{$row['address']}</td>
        <td>{$row['city']}</td>
        <td>{$row['province']}</td>
        <td><a href='?page=delete_user&id={$row['id']}' onclick='return confirm(\"Delete user?\")'>Delete</a></td>
        </tr>";
    }
    echo "</table>";

// ------------------- DELETE USER -------------------
} elseif($page === 'delete_user') {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: index.php?page==users");
}
?>
</div>
</body>
</html>
