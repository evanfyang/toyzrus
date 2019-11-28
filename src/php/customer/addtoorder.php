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
    echo "window.location.href='./shoppingcart.php' </script>"; 
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
        echo "window.location.href='./shoppingcart.php' </script>";
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
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='./shoppingcart.php' </script>";
        exit();
    }
    // insert all products into order table
    while ($order = $shoppingCartQueryResult->fetch_array(MYSQLI_ASSOC)) {
        $productID = $order["prodID"];
        $quantity = $order["quantity"];
        $updateProductQuantityQuery = "UPDATE Products SET inventory = 
            inventory - '$quantity' WHERE productID = '$productID' AND 
            inventory >= '$quantity' AND inventory > 0";
        $updateProductQuantityQueryResult = $mysqli->query($updateProductQuantityQuery);
        if (!$updateProductQuantityQueryResult) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); "; 
            echo "window.location.href='./shoppingcart.php' </script>";
            exit();
        }
        else if ($mysqli->affected_rows==0) {
            $productNameQuery = "SELECT name FROM Products WHERE productID='$productID'";
            $productNameQueryResult = $mysqli->query($productNameQuery);
            if (!$productNameQueryResult) {
                echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
                echo "Please try again later. Click 'OK' to go back.\"); "; 
                echo "window.location.href='./shoppingcart.php' </script>";
                exit();
            }
            $product = $productNameQueryResult->fetch_array(MYSQLI_ASSOC);
            echo '<script>alert("Unable to order the specified quantity for ';
            echo 'the product \'' . $product["name"] . '\'. Please reduce the ';
            echo 'quantity or wait for the item to restock."); ';
            echo 'window.location.href="./shoppingcart.php";</script>';
            exit();
        }
        else { 
            $addOrderQuery = "INSERT INTO Orders (orderID, userID, prodID, 
                quantity, status, money_saved, order_datetime) VALUES 
                ('$newOrderID', '$userID', '$productID', '$quantity', 'Pending', 
                0, NOW())";
            $addOrderQueryResult = $mysqli->query($addOrderQuery);
            if (!$addOrderQueryResult) {
                echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
                echo "Please try again later. Click 'OK' to go back.\"); "; 
                echo "window.location.href='./shoppingcart.php' </script>";
                exit();
            }
        }
    }
    // Remove items added to order from shopping cart
    $removeFromShoppingCartQuery = "DELETE FROM ShoppingBasket WHERE userID = '$userID'";
    $result = $mysqli->query($removeFromShoppingCartQuery);
    if (!$result) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='./shoppingcart.php' </script>";
        exit;
    }
    header('Location: ./orders.php');
    echo '<script>alert("Successfully placed order!")</script>';
    exit();
}
?>
