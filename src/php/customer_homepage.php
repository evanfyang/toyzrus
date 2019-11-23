<?php
session_start();
if(!isset($_SESSION['username']))
{
    // not logged in
    header('Location: ../../index.html');
    exit();
}

$userID = $_SESSION['userID'];
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
  <div style="float:right">
    <a href="./customer_homepage.php" class="active">Home</a>
    <a href="./products.php">Products</a>
    <a href="./customer_orders.php">Orders</a>
    <a href="customer_shoppingcart.php">Shopping Cart</a>
    <a href="logout.php" class="logout">Logout</a>
  </div>
  <div style="float:left">
    <h2> ToyzRUs </h2>
  </div>
</div>

<div class="imgcontainer">
    <h1>ToyzRUs Homepage</h1>
    <h2>Welcome, <?php echo "$username"?>!</h2>
    <p> Please select one of the links  
        <br> above to start shopping!</p> 
    <img src="../assets/homepagelogo.png" alt="Avatar" class="avatar">
</div>
</body>
</html>
