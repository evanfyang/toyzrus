<?php

$username = $_SESSION['username'];
$password = $_SESSION['password'];

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../css/customer_homepage.css">
</head>
<body>

<div class="topnav" id="myTopnav">
  <a href="#home" class="active">Home</a>
  <a href="#toys">Toys</a>
  <a href="#books">Books</a>
  <a href="#orders">Orders</a>
  <a href="#cart">Shopping Cart</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>

<div class="imgcontainer">
    <h1>ToyzRUs Homepage</h1>
    <h2>Welcome, <?php echo $username ?>!</h2>
    <p> Please select one of the links  
        <br> above to start shopping!</p> 
    <img src="../assets/homepagelogo.png" alt="Avatar" class="avatar">
    <button type="button" onclick="logout()" class="secondarybtn"> Logout </button>
</div>

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