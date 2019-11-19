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
  else {
    $row = $result->fetch_array(MYSQLI_ASSOC);
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
    <img src="../assets/shoppingcartlogo.png" alt="Avatar" class="avatar">
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
      
	  if ($result->num_rows != 0) {
	  	do {
        	echo '<tr>';
        	echo '<td><center><button name="id" value="' . $row["productID"] .'" type="submit" onclick="removeFromCartAlert()"> Remove from Cart </button></center></td>';
			echo '<td>' . $row["name"] . '</td>';
        	echo '<td>' . $row["category"] . '</td>';
        	echo '<td>' . $row["price"] . '</td>';
			echo '</tr>';
      	} while ($row = $result->fetch_array(MYSQLI_ASSOC));
	  } 
	  echo '</table>';
	  echo '</form>';

      $mysqli->close();
  ?>
</div>
<script>
function removeFromCartAlert() {
	alert("Item removed successfully from cart!");
}
</script>
</body>
</html>
