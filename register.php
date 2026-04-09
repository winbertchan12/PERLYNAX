<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";
$error = "";

if(isset($_POST['register'])){

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $province = trim( $_POST['province']);

    if(empty($username) || empty($email) || empty($password) || empty($address) || empty($city) || empty($province)){
        $error = "All fields are required.";
    } else {

        // CHECK IF USER EXISTS
        $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $result = $check->get_result();

        if($result->num_rows > 0){
            $error = "Username or Email already exists.";
        } else {

            // HASH PASSWORD
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // INSERT USER
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, address, city, province) VALUES (?, ?, ?,?,?,?)");

            if(!$stmt){
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssssss", $username, $email, $hashed_password,$address,$city,$province);

            if($stmt->execute()){
                $message = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - PERLYNAX</title>

<style>
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #1f1f2e, #3a3a5a);
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.card {
    background:#fff;
    padding:40px;
    border-radius:15px;
    width:350px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    text-align:center;
}

.card h1 {
    margin-bottom:20px;
}

input {
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

button {
    width:100%;
    padding:12px;
    background:#28a745;
    border:none;
    color:#fff;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

button:hover {
    background:#1e7e34;
}

.error {
    color:red;
    margin-bottom:10px;
}

.success {
    color:green;
    margin-bottom:10px;
}

.link {
    margin-top:15px;
}

.link a {
    text-decoration:none;
    color:#007bff;
}
</style>
</head>
<body>

<div class="card">
    <h1>Register</h1>

    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(!empty($message)): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="address" placeholder="Full Adress" required>
        <input type="text" name="city" placeholder="Town or City" required>
        <input type="text" name="province" placeholder="Province" required>

        <button type="submit" name="register">Register</button>
    </form>

    <div class="link">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

</body>
</html>
