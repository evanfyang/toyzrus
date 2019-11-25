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
  exit();
}
else {
    // find a unique orderID
    $orderIDQuery = "SELECT orderID FROM Orders";
    $orderIDQueryResult = $mysqli->query($orderIDQuery);
    $orderIDs = $orderIDQueryResult->fetch_array(MYSQLI_ASSOC);
    if (!$orderIDQueryResult) {
        echo "Query failed: " . $mysqli->error . "\n";
        exit();
    }
	  $uniqueID = false;
    do {
        $newOrderID = rand();
        if (!in_array(newOrderID, $orderIDs)) {
            $uniqueID = true;
        }
    } while (!uniqueID);
    // get all products associated with a particular userID
    $shoppingCartQuery = "SELECT * FROM ShoppingBasket WHERE userID = '$userID'";
    $shoppingCartQueryResult = $mysqli->query($shoppingCartQuery);
    if (!$shoppingCartQueryResult) {
      echo "Query failed: " . $mysqli->error . "\n";
      exit();
    }
    // insert all products into order table
    while ($order = $shoppingCartQueryResult->fetch_array(MYSQLI_ASSOC)) {
      $productID = $order["prodID"];
      $quantity = $order["quantity"]; 
      $addOrderQuery = "INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES ('$newOrderID', '$userID', '$productID', '$quantity', 'Pending', 0, NOW())";
      $addOrderQueryResult = $mysqli->query($addOrderQuery);
      if (!$addOrderQueryResult) {
        echo "Query failed: " . $mysqli->error . "\n";
        exit();
      }  
    }
    $removeFromShoppingCartQuery = "DELETE FROM ShoppingBasket WHERE userID = '$userID'";
    $result = $mysqli->query($removeFromShoppingCartQuery);
    if (!$result) {
      echo "Query failed: " . $mysqli->error . "\n";
      exit;
    }
    header('Location: ./customer_orders.php');
    echo '<script>alert("Successfully placed order!")</script>';
    exit();
  }
  ?>
