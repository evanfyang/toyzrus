<?php
// start session
session_start();
if(!isset($_SESSION['username'])) {
    // not logged in
    header('Location: ../../../index.html');
    exit();
}

// get user ID, username, and password from session
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../../css/customer.css">
	<link rel="stylesheet" type="text/css" href="../../css/index.css">
</head>
<body>

<div class="topnav" id="myTopnav">
    <div style="float:left">  
        <p style="float:left; color:#f2f2f2; text-align: center; text-decoration: none; 
        font-size: 17px; margin-left:10px; margin-bottom:0px"> ToyzRUs </p>
    </div>
    <div style="float:right">
        <a href="./homepage.php">Home</a>
        <a href="./inventory.php" class="active">Inventory</a>
        <a href="./orders.php">Orders</a>
        <a href="javascript:void(0);" onclick="logout()">Logout</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
</div>

<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } 
    else {
        x.className = "topnav";
    }
}
function logout() {
    if (confirm("Are you sure you want to logout?")) {
        window.location="../logout.php";
    }
}
</script>

<form action="./addnewproduct.php" method="POST">
  <div class="imgcontainer">
    <h1>Add New Product</h1>
    <p> Please fill out the following fields about 
        <br> the new product to add to inventory.</p>
    <img src="../../assets/newproductlogo.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="productname"><b>Product Name</b></label>
    <input type="text" placeholder="Enter Product Name" name="productname" id="productname" required>

	<label for="category"><b>Product Category</b></label>
    <input type="text" placeholder="Enter Product Category" name="category" id="category" required>
	
	<label for="price"><b>Price </b></label>
    <input type="text" placeholder="Enter Price (in USD)" name="price" id="price" required>

    <label for="quantity"><b>Quantity</b></label>
    <input type="text" placeholder="Enter Product Quantity" name="quantity" id="quantity" required>

    <button type="submit">Add New Product!</button>
    <button type="button" onclick="goToInventory()" class="secondarybtn"> Go Back to Inventory </button>
  </div>
</form>

<script>
function goToInventory() {
    window.location.href = './inventory.php';
}
</script>

</body>
</html>
