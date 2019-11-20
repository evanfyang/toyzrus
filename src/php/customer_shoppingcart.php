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

// connect to mysql
$host = "localhost";
$mysqlUser = "root";
$mysqlPassword = "pwd";
$mysqldb = "ecommerce";
$mysqli = new mysqli($host, $mysqlUser, $mysqlPassword, $mysqldb);

// check connection
if ($mysqli->connect_errno) {
  echo "Could not connect to database \n";
  echo "Error: ". $mysqli->connect_error . "\n";
  exit;
}
else {
  // validate user login by querying form value
  $query = "SELECT * FROM (SELECT * FROM ShoppingBasket) AS ShoppingCart JOIN (SELECT * FROM Products) AS AllProducts ON ShoppingCart.prodID = AllProducts.productID WHERE userID='$userID'";
  $result = $mysqli->query($query);
  if (!$result) {
    echo "Query failed: " . $mysqli->error . "\n";
    exit;
  }
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
  <a href="./products.php">Products</a>
  <a href="./customer_orders.php">Orders</a>
  <a href="customer_shoppingcart.php" class="active">Shopping Cart</a>
</div>


<div class="imgcontainer">
    <h1>Shopping Cart</h1>
    <img src="../assets/ShoppingCartLogo.png" alt="Avatar" class="avatar">
</div>

<div>
  <?php
      echo '<form action="./removefromcart.php" method="POST">';
	    echo '<table>';
      echo '<tr>';
	    echo '<th> Remove Item </th>';
      echo '<th> Product Name </th>';
      echo '<th> Category </th>';
      echo '<th> Price </th>';
      echo '</tr>';
	  	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        	echo '<tr>';
        	echo '<td><center><button name="id" value="' . $row["productID"] .'" type="submit" onclick="removeFromCartAlert()"> Remove from Cart </button></center></td>';
			    echo '<td>' . $row["name"] . '</td>';
        	echo '<td>' . $row["category"] . '</td>';
        	echo '<td>$' . $row["price"] . '</td>';
			    echo '</tr>';
      }
	  echo '</table>';
	  echo '</form>';
  ?>
</div>

<script>
function removeFromCartAlert() {
  if (confirm("Are you sure you want to remove this item from your cart?")) {
    alert("Item removed successfully from cart!");
  }
}
</script>

<?php

$query = "SELECT SUM(price) as total FROM (SELECT * FROM ShoppingBasket) AS ShoppingCart JOIN (SELECT * FROM Products) AS AllProducts ON ShoppingCart.prodID = AllProducts.productID WHERE userID='$userID' GROUP BY userID";
$result = $mysqli->query($query);
if (!$result) {
  exit;
}
else {
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $total = number_format($row["total"], 2, '.', '');
  $tax = number_format($row["total"] * 0.06, 2, '.', '');
  $subtotal = number_format($row["total"] + $row["total"] * 0.06, 2, '.', '');
  echo '<div style="float:right; margin-right:10px; text-align:right">';  
  echo '<div style="float:left; margin-top:0px">';
  echo '<p style="float:right"><b> Total:&nbsp <br>Sales Tax:&nbsp <br>Subtotal:&nbsp </b></p>';
  echo '</div>';
  echo '<div style="float:right; margin-top:0px">';
  echo '<p style="float:right"><b>$' . $total . '<br>';
  echo '$' . $tax . '<br>$' . $subtotal . '</b></p>';
  echo '</div>';
  echo '</div>';
}

$mysqli->close();

?>

<br?>

<button type="button" onclick="addToOrder()" class="primarybtn"> Click Here to Order! </button>

<script>
function addToOrder() {
  if (confirm("Are you sure you want to place this order?")) {
    <?php header("Location: ./customer_orders.php"); ?>
  }
}
</script>

</body>
</html>
