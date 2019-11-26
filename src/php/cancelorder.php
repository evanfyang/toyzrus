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

$orderID = $_POST['orderID'];

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
	$query = "SELECT order_datetime FROM Orders WHERE userID='$userID' AND orderID='$orderID'";
	$result = $mysqli->query($query);
	if (!$result) {
      echo "Query failed: " . $mysqli->error . "\n";
      exit();
    }
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$order_datetime = strtotime($row["order_datetime"]);
	$now = time();
	$days_between = floor(abs($now - $order_datetime) / 86400);
	if ($days_between >=1) {
		//header('Location: ./customer_orders.php');
     	echo '<script>alert("Sorry, we cannot cancel order ' . $orderID . ' since it was placed more than 24 hours ago."); window.location="./customer_orders.php";</script>';
		exit();
	}
	else {
		$query = "UPDATE Orders SET status='Canceled' WHERE userID='$userID' AND orderID='$orderID'";
    	$result = $mysqli->query($query);
    	if (!$result) {
      		echo "Query failed: " . $mysqli->error . "\n";
      		exit();
    	}
    	header('Location: ./customer_orders.php');
    	echo '<script>alert("Successfully canceled order ' . $orderID . '!")</script>';
    	exit();

	}
}
?>
