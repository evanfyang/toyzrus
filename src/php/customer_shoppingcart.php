<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];

if(!isset($_SESSION['username']))
{
    // not logged in
    header('Location: ../../index.html');
    exit();
}

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
  <a href="./customer_toys.php">Toys</a>
  <a href="./customer_books.php">Books</a>
  <a href="./customer_orders.php">Orders</a>
  <a href="customer_shoppingcart.php" class="active">Shopping Cart</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>

<!--
<div class="imgcontainer">
    <h1>ToyzRUs Homepage</h1>
    <h2>Welcome, <?php echo "$username"?>!</h2>
    <p> Please select one of the links  
        <br> above to start shopping!</p> 
    <img src="../assets/homepagelogo.png" alt="Avatar" class="avatar">
</div>
-->

<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}

function logout() {
    <?php session_unset() ?>
    window.location.href = '../../index.html';
}

</script>

</body>
</html>
