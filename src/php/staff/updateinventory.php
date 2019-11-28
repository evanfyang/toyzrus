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

// get product ID and product quantity from form submission
$productID = $_POST["productID"];
$quantity = $_POST["quantity"]; 

// connect to mysql
$host = "localhost";
$mysqlUser = "root";
$mysqlPassword = "pwd";
$mysqldb = "ecommerce";
$mysqli = new mysqli($host, $mysqlUser, $mysqlPassword, $mysqldb);

// check connection
if ($mysqli->connect_errno) {
    echo '<script> alert("Could not connect to database';
    echo 'Error: ' . $mysqli->connect_error . '. ';
    echo 'Please try again another time."); '; 
    echo 'window.location.href="./homepage.php"'; 
    exit;
}
else {
    // Update the quantity of given product in user's shopping cart
    $query = "UPDATE Products SET inventory = '$quantity' WHERE 
        productID = '$productID'";
    $result = $mysqli->query($query);
    if (!$result) {
        echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
        echo 'Please try again later. Click \'OK\' to go back.");'; 
        echo 'window.location.href=./inventory.php </script>';
        exit;
    }
    else {
        header('Location: ./inventory.php');
        exit();
    }
}
?>

