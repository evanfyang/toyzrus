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
    // get all products associated with a particular orderID
    $shoppingCartQuery = "SELECT * FROM Orders WHERE orderID = '$orderID'";
    $shoppingCartQueryResult = $mysqli->query($shoppingCartQuery);
    if (!$shoppingCartQueryResult) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='./orders.php'; </script>";
        exit();
    }
    $lowStock = false;
    $lowStockProductIDs = [];
    $lowStockProductName = [];
    $lowStockProductPrice = [];
    $lowStockProductQuantity = [];
    $lowStockProductCategory = [];
    while ($order = $shoppingCartQueryResult->fetch_array(MYSQLI_ASSOC)) {
        $productID = $order["prodID"];
        $quantity = $order["quantity"];
        // Check if a product has enough stock to ship
        $checkProductQuantityQuery = "SELECT * FROM Products WHERE 
            productID = '$productID' AND inventory >= '$quantity' AND 
            inventory > 0";
        $checkProductQuantityQueryResult = $mysqli->query($checkProductQuantityQuery);
        // Check if query failed
        if (!$checkProductQuantityQueryResult) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); "; 
            echo "window.location.href='./orders.php'; </script>";
            exit();
        }
        // Not enough of a particular product to ship
        else if ($mysqli->affected_rows==0) {
            $lowStock = true;
            // Get all information on low stock product
            $productNameQuery = "SELECT * FROM Products WHERE productID='$productID'";
            $productNameQueryResult = $mysqli->query($productNameQuery);
            // Check if query failed
            if (!$productNameQueryResult) {
                echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
                echo "Please try again later. Click 'OK' to go back.\"); "; 
                echo "window.location.href='./orders.php'; </script>";
                exit();
            }
            // Store information about product with low stock
            $product = $productNameQueryResult->fetch_array(MYSQLI_ASSOC);
            $lowStockProductIDs[] = $product["productID"];
            $lowStockProductName[] = $product["name"];
            $lowStockProductPrice[] = $product["price"];
            $lowStockProductQuantity[] = $product["inventory"];
            $lowStockProductCategory[] = $product["category"];
        }
    }
    if($lowStock) {
       	?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="../../css/customer.css">
        </head>
        <body>
        <div class="topnav" id="myTopnav">
            <div style="float:left">  
                <p style="float:left; color:#f2f2f2; text-align: center; text-decoration: none; 
                font-size: 15px; margin-left:10px; margin-bottom:0px"> ToyzRUs </p>
            </div>
            <div style="float:right">
                <a href="./homepage.php">Home</a>
                <a href="./inventory.php">Inventory</a>
                <a href="./promotions.php">Promotions</a>
                <a href="./orders.php" class="active">Orders</a>
                <a href="./statistics.php">Statistics</a>
                <a href="javascript:void(0);" onclick="logout()">Logout</a>
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div>
        <script>
        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } 
            else {
                x.className = "topnav";
            }
        }
        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location="../logout.php";
            }
        }
        </script>
        <div class="imgcontainer">
            <h1 style="color:red">Unable to Ship <br> Order
				<?php echo $orderID; ?></h1>
			 <img src="../../assets/staffordercantshiplogo.png" alt="Avatar" class="avatar">
            <h4> The following items listed in the <br> table below are low in stock and <br> must be restocked before shipping. </h4>
        </div>
        <div>
		<?php
        // Display each order in tabular format
        // Table headers
        echo '<br>';
        echo '<table>';
        echo '<tr>';
        //echo '<th> Product ID </th>';
        echo '<th> Product Name </th>';
        echo '<th> Category </th>';
        echo '<th> Price </th>';
        echo '<th> Quantity </th>';
        echo '</tr>';
        for ($i = 0; $i < sizeOf($lowStockProductIDs); $i++) {
            echo '<tr>';
            //echo '<td>' . $lowStockProductIDs[$i] . '</td>';
            echo '<td>' . $lowStockProductName[$i] . '</td>';
            echo '<td>' . $lowStockProductCategory[$i] . '</td>';
            echo '<td>$' . $lowStockProductPrice[$i] . '</td>';
            echo '<td>' . $lowStockProductQuantity[$i] . '</td>';
            echo '</tr>';
        }
		echo '</table>';
        echo '<br><br>';
        echo '<center><button type="button" onclick="';
        echo 'javascript:window.location.href=\'./orders.php\'" ';
        echo 'class="secondarybtn"> Go Back to Orders </button></center>';
		echo '</div>';
		echo '</body>';
		echo '</html>';
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
		// get all products associated with a particular orderID
    	$shoppingCartQuery = "SELECT * FROM Orders WHERE orderID = '$orderID'";
    	$shoppingCartQueryResult = $mysqli->query($shoppingCartQuery);
    	if (!$shoppingCartQueryResult) {
        	echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        	echo "Please try again later. Click 'OK' to go back.\"); "; 
        	echo "window.location.href='./shoppingcart.php'; </script>";
        	exit();
    	}
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
            	echo "window.location.href='./shoppingcart.php'; </script>";
            	exit();
        	}
		}
        // Go back to orders page 
        echo '<script> alert("Successfully shipped order ' . $orderID . '!"); ';
		echo 'window.location.href="./orders.php";</script>';
		exit();
    }
}
?>
