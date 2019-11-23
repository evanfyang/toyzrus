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
  $query = "SELECT * FROM (SELECT * FROM Orders WHERE userID='$userID') AS AllOrders JOIN (SELECT * FROM Products) AS AllProducts ON AllOrders.prodID = AllProducts.productID";
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
  <div style="float:left">  
  <p style="float:left; color:#f2f2f2; text-align: center; text-decoration: none; 
    font-size: 17px; margin-left:10px; margin-bottom:0px"> ToyzRUs </p>
  </div>
  <div style="float:right">
    <a href="./customer_homepage.php">Home</a>
    <a href="./products.php">Products</a>
    <a href="./customer_orders.php" class="active">Orders</a>
    <a href="customer_shoppingcart.php">Shopping Cart</a>
    <a href="logout.php">Logout</a>
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
  } else {
    x.className = "topnav";
  }
}
</script>

<div class="imgcontainer">
    <h1>Orders</h1>
    <img src="../assets/orderslogo.png" alt="Avatar" class="avatar">
</div>

<div>
  <?php
      echo '<form action="./removefromcart.php" method="POST">';
	  echo '<table>';
      echo '<tr>';
      echo '<th> Order ID </th>';
	  echo '<th> Order Status </th>';
      echo '<th> Product Name </th>';
      echo '<th> Category </th>';
      echo '<th> Price </th>';
      echo '</tr>';
	  	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        	echo '<tr>';
          	echo '<td>' . $row["orderID"] . '</td>';
		 	echo '<td>' . $row["status"] . '</td>';
			echo '<td>' . $row["name"] . '</td>';
        	echo '<td>' . $row["category"] . '</td>';
        	echo '<td>$' . $row["price"] . '</td>';
			echo '</tr>';
    }
	  echo '</table>';
	  echo '</form>';
  ?>
</div>

</body>
</html>
