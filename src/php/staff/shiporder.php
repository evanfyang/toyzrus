<?php
// start session
session_start();
if(!isset($_SESSION['username']))
{
    // not logged in
    header('Location: ../../../index.html');
    exit();
}
// get user ID, username, and password from session
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

// get order id from form submission 
$orderID = $_POST['orderID'];

// connect to mysql
$host = "localhost";
$mysqlUser = "root";
$mysqlPassword = "pwd";
$mysqldb = "ecommerce";
$mysqli = new mysqli($host, $mysqlUser, $mysqlPassword, $mysqldb);

// check connection
if ($mysqli->connect_errno) {
    echo "<script> alert(\"Could not connect to database";
    echo "Error: " . $mysqli->connect_error . ". ";
    echo "Please try again another time. Click 'OK' to go back.\"); ";
    echo "window.location.href='./orders.php'; </script>"; 
    exit();
}
else {
    // update order status to 'Shipped'
    $query = "UPDATE Orders SET status='Shipped' WHERE orderID='$orderID'";
    $result = $mysqli->query($query);
    if (!$result) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='./orders.php'; </script>";
        exit();
    }
    // Go back to orders page
    header('Location: ./orders.php');
    echo '<script>alert("Successfully shipped order ' . $orderID . '!")</script>';
    exit();
}
?>