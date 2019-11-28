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

// get product id from product page form
$productID = $_POST["id"];

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
    echo 'window.location.href="./shoppingcart.php"'; 
    exit;
}
else {
    // Get all items in cart for a particular user
    $query = "SELECT * FROM ShoppingBasket WHERE userID='$userID' AND prodID='$productID'";
    $result = $mysqli->query($query);
    // Check if query fails
    if (!$result) {
        echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
        echo 'Please try again later. Click \'OK\' to go back.");'; 
        echo 'window.location.href=./shoppingcart.php </script>';
        exit();
    }
    // Check if item is already in cart. If not, add to cart.
    if (!$result->fetch_array(MYSQLI_ASSOC)) {
        $query = "INSERT INTO ShoppingBasket (userID, prodID, quantity) VALUES 
            ('$userID', '$productID', '1')";
        $result = $mysqli->query($query);
        // Check if query fails
        if (!$result) {
            echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
            echo 'Please try again later. Click \'OK\' to go back.");'; 
            echo 'window.location.href=./shoppingcart.php </script>';
            exit;
        }
        // Go back to product page
        else {
            header('Location: ./products.php');
            exit();
        }
    }
    // If item is already in shopping cart, update its quantity
    else {
        $query = "UPDATE ShoppingBasket SET quantity=quantity+1 WHERE 
            userID = '$userID' AND prodID = '$productID'";
        $result = $mysqli->query($query);
        // Check if query fails
        if (!$result) {
            echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
            echo 'Please try again later. Click \'OK\' to go back.");'; 
            echo 'window.location.href=./shoppingcart.php </script>';
            exit;
        }
        // Go back to products page
        else {
            header('Location: ./products.php');
            exit();
        }
    }
}
?>
