<!DOCTYPE html>
<html>
<head>
<title>Welcome</title>
<link rel="stylesheet" href="style.css">
<style>
.center-box{
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  height:100vh;
  text-align:center;
}
.btn{
  margin:10px;
  padding:12px 20px;
  background:gold;
  color:#000;
  border:none;
  cursor:pointer;
  font-weight:bold;
}
.btn:hover{background:white}
</style>
</head>
<body>
<div class="center-box">
  <h1>Welcome to PERLYNAX<sub style='color:white;'><?= 'Jewelry' ?></sub></h1>
  <p>Do you have an account?</p>
  <a href="login.php"><button class="btn">Login</button></a>
  <p>New customer?</p>
  <a href="register.php"><button class="btn">Create Account</button></a>
</div>
</body>
</html>
