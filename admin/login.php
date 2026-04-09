<?php
session_start();
include '../config.php';

if(isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Admin credentials (hardcoded for simplicity, replace with DB for multiple admins)
    $admin_user = "admin";
    $admin_pass = "admin123"; // you can hash this for security

    if($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin/index.php");
        exit();
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - PERLYNAX</title>
<link rel="stylesheet" href="../style.css">
<style>
body { display:flex; justify-content:center; align-items:center; height:100vh; }
form { border:1px solid #ccc; padding:30px; border-radius:10px; }
input { display:block; margin:10px 0; padding:8px; width:100%; }
button { padding:10px 20px; cursor:pointer; }
</style>
</head>
<body>
<form method="post">
<h2>Admin Login</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>
</body>
</html>
