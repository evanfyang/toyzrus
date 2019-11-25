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

$productID = $_POST["id"];

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

  $query = "SELECT * FROM ShoppingBasket WHERE userID='$userID' AND prodID='$productID'";
  $result = $mysqli->query($query);
  if (!$result) {
        echo "Query failed: " . $mysqli->error . "\n";
        exit();
  }
  if (!$result->fetch_array(MYSQLI_ASSOC)) {
    $query = "INSERT INTO ShoppingBasket (userID, prodID, quantity) VALUES ('$userID', '$productID', '1')";
    $result = $mysqli->query($query);
    if (!$result) {
      echo "Query failed: " . $mysqli->error . "\n";
      exit;
    }
    else {
      header('Location: ./products.php');
      exit();
    }
  }
  else {
     $query = "UPDATE ShoppingBasket SET quantity=quantity+1 WHERE userID = '$userID' AND prodID = '$productID'";
    $result = $mysqli->query($query);
    if (!$result) {
      echo "Query failed: " . $mysqli->error . "\n";
      exit;
    }
    else {
      header('Location: ./products.php');
      exit();
    }
  }
}
?>
