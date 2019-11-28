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

// get new product information from add new product form
$productname = $_POST["productname"];
$category = $_POST["category"];
$price = $_POST["price"];
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
    echo 'window.location.href="./shoppingcart.php"'; 
    exit;
}
else {
    // Add new product to inventory
    $query = "SELECT * FROM Products WHERE name='$productname' AND category='$category' AND price='$price'";
    $result = $mysqli->query($query);
    // Check if query fails
    if (!$result) {
        echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
        echo 'Please try again later. Click \'OK\' to go back.");'; 
        echo 'window.location.href=./shoppingcart.php </script>';
        exit();
    }
    // Check if product is in inventory. If not, add to inventory
    else if (!$result->fetch_array(MYSQLI_ASSOC)) {
        $query = "INSERT INTO Products (name, price, inventory, category) VALUES ('$productname', '$price', '$quantity', '$category')";
		$result = $mysqli->query($query);
		// Check if query fails
        if (!$result) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please ensure that your field values are in the correct form ";
			echo "and try again. Click 'OK' to go back.\"); "; 
            echo "window.location.href=./addinventory.php </script>";
            exit;
        }
        // Go back to inventory page
        else {
            echo "<script> alert(\"Successfully added new product '" . $productname . "' ";
        	echo "to inventory! Click 'OK' to go back to inventory page.\"); "; 
        	echo "window.location.href='./inventory.php' </script>";
            exit;
        }
    }
	else {
		echo "<script> alert(\"Unable to add new product '" . $productname . "' ";
        echo "to inventory, product already exists in inventory. ";
		echo "Click 'OK' to go back to inventory page.\"); "; 
        echo "window.location.href='./inventory.php' </script>";
		exit;
	}
}
?>
