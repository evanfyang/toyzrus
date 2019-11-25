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
$cartIsEmpty = TRUE;
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
  exit();
}
else {
  // validate user login by querying form value
  $query = "SELECT * FROM (SELECT * FROM ShoppingBasket) AS ShoppingCart JOIN (SELECT * FROM Products) AS AllProducts ON ShoppingCart.prodID = AllProducts.productID WHERE userID='$userID'";
  $result = $mysqli->query($query);
  if (!$result) {
	echo "Query failed: " . $mysqli->error . "\n";
    exit();
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
    <a href="./customer_orders.php">Orders</a>
    <a href="customer_shoppingcart.php" class="active">Shopping Cart</a>
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
  } else {
    x.className = "topnav";
  }
}
</script>

<div class="imgcontainer">
    <h1>Shopping Cart</h1>
    <img src="../assets/ShoppingCartLogo.png" alt="Avatar" class="avatar">
</div>

<div>
  <?php
	  echo '<table>';
    echo '<tr>';
	  echo '<th> Remove Item </th>';
    echo '<th> Product Name </th>';
    echo '<th> Category </th>';
	  echo '<th> Quantity </th>';
    echo '<th> Each </th>';
	  echo '<th> Total </th>';
    echo '</tr>';
	  	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$cartIsEmpty = FALSE;
        echo '<tr>';
        echo '<form action="./removefromcart.php" method="POST"><td><center><button name="id" value="' . $row["productID"] .'" type="submit" onclick="removeFromCartAlert()"> Remove from Cart </button></center></td></form>';
			  echo '<td>' . $row["name"] . '</td>';
        echo '<td>' . $row["category"] . '</td>';
        echo '<form action="./updatequantity.php" method="POST"><input type=hidden name="productID" value ="' . $row["productID"] . '" style="display:none"></input><td><center><input type=number  name="quantity" value="' . $row["quantity"] .'" style="width:3em; text-align:center" oninput=this.form.submit()></input></center></td></form>';
        echo '<td>$' . $row["price"] . '</td>';
			  echo '<td>$' . $row["price"]*$row["quantity"] . '</td>';
			  echo '</tr>';
      }
	  echo '</table>';
  ?>
</div>

<script>
function removeFromCartAlert() {
    alert("Item removed successfully from cart!");
}
</script>

<?php
$query = "SELECT price, quantity FROM (SELECT * FROM ShoppingBasket) AS ShoppingCart JOIN (SELECT * FROM Products) AS AllProducts ON ShoppingCart.prodID = AllProducts.productID WHERE userID='$userID'";
$result = $mysqli->query($query);
if (!$result) {
  exit;
}
else {
  $true_prices = [];
  $prices = [];
  $quantities = [];
  while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $prices[] = $row["price"];
    $quantities[] = $row["quantity"];
  }
  $true_total = 0;
  for ($i = 0; $i <= sizeOf($prices); $i++) {
     $true_prices[] = $prices[$i] * $quantities[$i];
     $true_total += $true_prices[$i];
  }
  $total = number_format($true_total, 2, '.', '');
  $tax = number_format($true_total * 0.06, 2, '.', '');
  $subtotal = number_format($true_total + $true_total * 0.06, 2, '.', '');
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

<br><br><br><br><br>

<center><button type="button" onclick="addToOrder()" class="primarybtn"> Click Here to Order! </button></center>

<script>
function addToOrder() {
  var cartIsEmpty = "<?php echo $cartIsEmpty; ?>";
  if (cartIsEmpty) {
	alert("Your shopping cart is empty!");
  }
  else if (confirm("Are you sure you want to place this order?")) {
   window.location="./addtoorder.php";
  }
}
function logout() {
    if (confirm("Are you sure you want to logout?")) {
    window.location="./logout.php";
  }
}
</script>

</body>
</html>
