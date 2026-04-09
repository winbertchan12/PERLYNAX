<?php session_start();
$id = $_GET['id'];
$action = $_GET['action'];

if(isset($_SESSION['cart'][$id])){
  if($action == 'inc'){
    $_SESSION['cart'][$id]++;
  } elseif($action == 'dec'){
    $_SESSION['cart'][$id]--;
    if($_SESSION['cart'][$id] <= 0){
      unset($_SESSION['cart'][$id]);
    }
  }
}
header('Location: cart.php');
?>
