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
    echo "window.location.href='./shoppingcart.php'; </script>"; 
    exit();
}
else {
    // find a new unique orderID
    $orderIDQuery = "SELECT orderID FROM Orders";
    $orderIDQueryResult = $mysqli->query($orderIDQuery);
    $orderIDs = $orderIDQueryResult->fetch_array(MYSQLI_ASSOC);
    if (!$orderIDQueryResult) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='./shoppingcart.php'; </script>";
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
    $shoppingCartQuery = "SELECT * FROM (SELECT * FROM ShoppingBasket) 
        AS ShoppingCart JOIN (SELECT * FROM Products) AS AllProducts ON 
        ShoppingCart.prodID = AllProducts.productID WHERE userID='$userID'";
    $shoppingCartQueryResult = $mysqli->query($shoppingCartQuery);
    if (!$shoppingCartQueryResult) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='./shoppingcart.php'; </script>";
        exit();
    }
    // insert all products into order table
    while ($order = $shoppingCartQueryResult->fetch_array(MYSQLI_ASSOC)) {
        $productID = $order["prodID"];
        $quantity = $order["quantity"];
		$price = $order["price"];
		$promotions = $order["promotions"]; 
		$discount = $price * ($promotions/100);
        $addOrderQuery = "INSERT INTO Orders (orderID, userID, prodID, 
            quantity, status, money_saved, order_datetime) VALUES 
            ('$newOrderID', '$userID', '$productID', '$quantity', 'Pending', 
            '$discount', NOW())";
        $addOrderQueryResult = $mysqli->query($addOrderQuery);
        if (!$addOrderQueryResult) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); "; 
            echo "window.location.href='./shoppingcart.php'; </script>";
            exit();
        }
    }
    // Remove items added to order from shopping cart
    $removeFromShoppingCartQuery = "DELETE FROM ShoppingBasket WHERE userID = '$userID'";
    $result = $mysqli->query($removeFromShoppingCartQuery);
    if (!$result) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='./shoppingcart.php'; </script>";
        exit;
    }
    header('Location: ./orders.php');
    echo '<script>alert("Successfully placed order!")</script>';
    exit();
}
?>
