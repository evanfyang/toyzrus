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
  $query = "SELECT * FROM Products;";
  $result = $mysqli->query($query);
  if (!$result) {
    echo "Query failed: " . $mysqli->error . "\n";
    exit;
  }
  // incorrect product query
  else if ($result->num_rows == 0) {
    echo "<p>Something went wrong on our end. Please try again.</p>";
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
  <a href="./products.php" class="active">Products</a>
  <a href="./customer_orders.php">Orders</a>
  <a href="customer_shoppingcart.php">Shopping Cart</a>
</div>


<div class="imgcontainer">
  <h1>Products</h1>
  <img src="../assets/productslogo.png" alt="Avatar" class="avatar">
</div>


<div>
  <table>
    <tr>
      <th> Product Name </th>
      <th> Price </th>
      <th> Inventory </th>
      <th> Category </th>
      <th> Add Item </th>
    </tr>
  <?php
      // echo '<table>';
      // echo '<tr>';
      // echo '<th> Product Name </th>';
      // echo '<th> Price </th>';
      // echo '<th> Inventory </th>';
      // echo '<th> Category </th>';
      // echo '<th> Add Item </th>';
      // echo '</tr>';
      while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["name"] . '</td>';
        echo '<td>' . $row["price"] . '</td>';
        echo '<td>' . $row["inventory"] . '</td>';
        echo '<td>' . $row["category"] . '</td>';
        echo '</tr>';
        // echo "<li>$row["name"], $row["price"], $row["inventory"], $row["category"]</li>";
        //echo "<li>" . $row["name"] . ", " . $row["price"] . ", " . $row["inventory"] . ", " . $row["category"] . "</li>";
      }
      echo '</table>';

      $mysqli->close();
  ?>
</div>

</body>
</html>
