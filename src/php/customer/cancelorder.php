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
    echo '<script> alert("Could not connect to database';
    echo 'Error: ' . $mysqli->connect_error . '. ';
    echo 'Please try again another time."); ';
    echo 'window.location.href="./orders.php"'; 
    exit();
}
else {
    // get date and time order was placed
    $query = "SELECT order_datetime FROM Orders WHERE userID='$userID' AND orderID='$orderID'";
    $result = $mysqli->query($query);
    if (!$result) {
        echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
        echo 'Please try again later. Click \'OK\' to go back.");'; 
        echo 'window.location.href=./orders.php </script>';
        exit();
    }
    // If order was placed more than 24 hours ago, alert user that order cannot be canceled
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $order_datetime = strtotime($row["order_datetime"]);
    $now = time();
    $days_between = floor(abs($now - $order_datetime) / 86400);
    if ($days_between >=1) {
        echo '<script>alert("Sorry, we cannot cancel order ' . $orderID . ' ';
        echo 'since it was placed more than 24 hours ago."); '; 
        echo 'window.location="./orders.php";</script>';
        exit();
    }
    // Otherwise, update status of order to 'Canceled' and restock items
    else {
        // update order status to 'Canceled'
        $query = "UPDATE Orders SET status='Canceled' WHERE userID='$userID' AND orderID='$orderID'";
        $result = $mysqli->query($query);
        if (!$result) {
            echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
            echo 'Please try again later. Click \'OK\' to go back.");'; 
            echo 'window.location.href=./orders.php </script>';
            exit();
        }
        $query = "SELECT prodID, quantity FROM Orders WHERE orderID='$orderID'";
        $result = $mysqli->query($query);
        if (!$result) {
            echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
            echo 'Please try again later. Click \'OK\' to go back.");'; 
            echo 'window.location.href=./orders.php </script>';
            exit();
        }
        // Restock items
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $productID = $row["prodID"];
            $quantity = $row["quantity"];
            $cancelOrderRestockQuery = "UPDATE Products SET inventory=inventory+'$quantity' WHERE productID='$productID'";
            $cancelOrderRestockQueryResult = $mysqli->query($cancelOrderRestockQuery);
            if (!$cancelOrderRestockQueryResult) {
                echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
                echo 'Please try again later. Click \'OK\' to go back.");'; 
                echo 'window.location.href=./orders.php </script>';
                exit();
            }
        }
        // Go back to orders page
        header('Location: ./orders.php');
        echo '<script>alert("Successfully canceled order ' . $orderID . '!")</script>';
        exit();
    }
}
?>
