<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){
        $error = "Please fill in all fields.";
    } else {

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");

        if(!$stmt){
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){

            $user = $result->fetch_assoc();

            // ✅ SUPPORT BOTH HASHED + PLAIN PASSWORDS
            if(password_verify($password, $user['password']) || $password === $user['password']){
                $_SESSION['user_logged_in'] = true; // <- important
              
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: index.php");
                exit();

            } else {
                $error = "Invalid password.";
            }

        } else {
            $error = "Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - PERLYNAX</title>

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
    background:#007bff;
    border:none;
    color:#fff;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

button:hover {
    background:#0056b3;
}

.error {
    color:red;
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
    <h1>Login</h1>

    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Login</button>
    </form>

    <div class="link">
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</div>

</body>
</html>
