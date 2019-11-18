<?php
session_start();
if(!isset($_SESSION['username']))
{
    // not logged in
    header('Location: ../../index.html');
    exit();
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../css/customer.css">
</head>
<body>

<div class="topnav" id="myTopnav">
  <a href="./customer_homepage.php">Home</a>
  <a href="./products.php" class="active">Products</a>
  <a href="./customer_orders.php">Orders</a>
  <a href="customer_shoppingcart.php">Shopping Cart</a>
</div>


<div class="imgcontainer">
    <h1>Products</h1>
<!--
    <img src="../assets/homepagelogo.png" alt="Avatar" class="avatar">
-->
</div>

</body>
</html>
