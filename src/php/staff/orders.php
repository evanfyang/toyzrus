<?php
// start session
session_start();
if(!isset($_SESSION['username'])) {
    // not logged in
    header('Location: ../../../index.html');
    exit();
}
// get user ID, username, and password from current session
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
    echo "window.location.href='./homepage.php'; </script>"; 
    exit;
}
else {
    // get all orders from a particular user 
    $orderInfoQuery = "SELECT * FROM Orders ORDER BY order_datetime DESC";
    $orderInfoQueryResults = $mysqli->query($orderInfoQuery);
    if (!$orderInfoQueryResults) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); </script>"; 
        exit;
    }
    else {
        // store orderIDs, order statuses, and order datetimes 
        $orderIDs = [];
        $userIDs = [];
        $orderStatuses = [];
        $orderDatetimes = [];
        while($row = $orderInfoQueryResults->fetch_array(MYSQLI_ASSOC)) {
            if(!in_array($row["orderID"], $orderIDs)) {
                $orderIDs[] = $row["orderID"];
                $userIDs[] = $row["userID"];
                $orderStatuses[] = $row["status"];
                $orderDatetimes[] = $row["order_datetime"];
            }
        }
    }
}

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
        font-size: 17px; margin-left:10px; margin-bottom:0px"> ToyzRUs </p>
    </div>
    <div style="float:right">
        <a href="./homepage.php">Home</a>
        <a href="./inventory.php">Inventory</a>
        <a href="./orders.php" class="active">Orders</a>
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
    <h1>Orders</h1>
    <img src="../../assets/stafforderlogo.png" alt="Avatar" class="avatar">
</div>

<div>
<?php
    // Display message to user if there are no orders
    if (sizeOf($orderIDs) == 0) {
        echo '<center><h3 style="color:red"> No Orders to Display... <h3></center>';
    }
    // Display each order in tabular format
    for ($i = 0; $i < sizeOf($orderIDs); $i++) {
        // Table headers
        echo '<br><br>';
        echo '<table>';
        echo '<tr>';
        echo '<th> Product Name </th>';
        echo '<th> Category </th>';
        echo '<th> Quantity </th>';
        echo '<th> Each </th>';
        echo '<th> Total </th>';
        echo '</tr>';
        // Get product information from a particular order that the user placed
        $query = "SELECT * FROM (SELECT * FROM Orders WHERE userID='$userIDs[$i]' AND
            orderID='$orderIDs[$i]') AS AllOrders JOIN (SELECT * FROM Products) 
            AS AllProducts ON AllOrders.prodID = AllProducts.productID";
        $result = $mysqli->query($query);
        if (!$result) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); </script>"; 
            exit;
        }
        // Table row data
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row["name"] . '</td>';
            echo '<td>' . $row["category"] . '</td>';
            echo '<td>' . $row["quantity"] . '</td>';
            echo '<td>$' . $row["price"] . '</td>';
            echo '<td>$' . $row["quantity"] * $row["price"] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        // Display order ID, order status, and date + time order was placed
        echo '<div style="float:left; text-align:left">';
        echo '<p><b> Order ID: ' . $orderIDs[$i] . '<br>';
        if ($orderStatuses[$i] == "Canceled") {
            echo 'Order Status: <mark style="background-color:#F44336">';
            echo $orderStatuses[$i] . '</mark><br>';
        }
        else if ($orderStatuses[$i] == "Shipped") {
            echo 'Order Status: <mark style="background-color:#4F7FE4">';
            echo $orderStatuses[$i] . '</mark><br>';
        }
        else  /*($orderStatuses[$i] == "Pending")*/ {
            echo 'Order Status: <mark style="background-color:#FFE158">';
            echo $orderStatuses[$i] . '</mark><br>';
        }
        echo 'Order Placed On: ' . $orderDatetimes[$i] . '</b></p>';
        echo '</div>';
        // Get prices and quantities for products in an order
        $orderPriceQuery = "SELECT price, quantity FROM (SELECT * FROM Orders) 
            AS AllOrders JOIN (SELECT * FROM Products) AS AllProducts ON 
            AllOrders.prodID = AllProducts.productID WHERE userID='$userIDs[$i]' AND 
            orderID='$orderIDs[$i]'";
        $orderPriceQueryResult = $mysqli->query($orderPriceQuery);
        if (!$orderPriceQueryResult) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); </script>"; 
            exit;
        }
        else {
            $true_prices = [];
            $prices = [];
            $quantities = [];
            while($orderInfo = $orderPriceQueryResult->fetch_array(MYSQLI_ASSOC)) {
                $prices[] = $orderInfo["price"];
                $quantities[] = $orderInfo["quantity"];
            }
            // Calculate total price, tax and subtotal for an order
            $true_total = 0;
            for ($j = 0; $j <= sizeOf($prices); $j++) {
                $true_prices[] = $prices[$j] * $quantities[$j];
                $true_total += $true_prices[$j];
            }
            $total = number_format($true_total, 2, '.', '');
            $tax = number_format($true_total * 0.06, 2, '.', '');
            $subtotal = number_format($true_total + $true_total * 0.06, 2, '.', '');
            // Display total price, tax, and subtotal
            echo '<div style="float:right; margin-right:10px; text-align:right">';  
            echo '<div style="float:left; margin-top:0px">';
            echo '<p style="float:right"><b> Total:&nbsp <br>Sales Tax:&nbsp <br>Subtotal:&nbsp </b></p>';
            echo '</div>';
            echo '<div style="float:right; margin-top:0px">';
            echo '<p style="float:right"><b>$' . $total . '<br>';
            echo '$' . $tax . '<br>$' . $subtotal . '</b></p>';
            echo '</div>';
            echo '</div>';
            echo '<br><br><br><br><br>';
            if ($orderStatuses[$i] == "Pending") {
                echo '<form action="shiporder.php" method="POST" onsubmit="';
                echo 'return confirm(\'Are you sure you want to ship order ';
                echo $orderIDs[$i] . '? Once you ship an order, it cannot be ';
                echo 'undone!\');"><center><button type="submit" name="orderID" ';
                echo 'value="' . $orderIDs[$i] . '"class="primarybtn"> Ship ';
                echo 'Order ' . $orderIDs[$i] . '</button></center></form>';
            }
        }
    }
?>

<script>
function cancelOrder() {
    if (confirm("Are you sure you want to place this order?")) {
        window.location="./cancelorder.php";
    }
}
</script>
</div>
</body>
</html>
