<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

<!DOCTYPE html>

<link rel="stylesheet" href="style.css">
<header>
<header class="page-title-container">
<h1>PERLYNAX</h1>
<p>Jewelry</p>
</header>



<nav>
<a href="index.php">Home</a>
<?php 
$count = 0;
if(isset($_SESSION['cart'])){
  foreach($_SESSION['cart'] as $q){ $count += $q; }
}
?>
<a href="cart.php">Cart <sup style='color:gold;'><?= $count ?></sup></a>
<?php if(isset($_SESSION['user'])): ?>
<a href="logout.php">Logout</a>
<?php else: ?>
<a href="login.php">Login</a>
<?php endif; ?>
</nav>
</header>
