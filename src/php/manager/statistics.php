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
    echo "window.location.href='./homepage.php'; </script>"; 
    exit();
}
// get user firstname and lastname and store in current session
else {
    // Get all products from orders within the past week
    $query = "SELECT * FROM Products ORDER BY category;";
    $result = $mysqli->query($query);
    if (!$result) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        exit;
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
        font-size: 15px; margin-left:10px; margin-bottom:0px"> ToyzRUs </p>
    </div>
    <div style="float:right">
        <a href="./homepage.php">Home</a>
        <a href="./inventory.php">Inventory</a>
        <a href="./promotions.php">Promotions</a>
        <a href="./orders.php">Orders</a>
        <a href="./statistics.php" class="active">Statistics</a>
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
    <h1>Product Sales Statistics</h1>
    <img src="../../assets/statisticslogo.png" alt="Avatar" class="avatar">
	<br>
</div>

<br>

<div>
<?php
    // Display table header
    echo '<table>';
    echo '<tr>';
    echo '<th> Product Name </th>';      
    echo '<th> Category </th>';
    echo '<th> Amount Sold Last Week </th>';
    echo '<th> Amount Sold Last Month </th>';
	echo '<th> Amount Sold Last Year </th>';
    echo '</tr>';
    // Add products into table
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["name"] . '</td>';
        echo '<td>' . $row["category"] . '</td>';
        $productID = $row["productID"];
        // Get all products from orders within the past week
        $lastweek = new DateTime('-1 week');
        $lastweek = $lastweek->format('Y-m-d H:i:s');
        $pastWeekOrderAmountQuery = "SELECT SUM(quantity) as sum FROM Orders 
            WHERE prodID='$productID' AND status!='Canceled' AND 
            order_datetime>='$lastweek' GROUP BY prodID;";
        $pastWeekOrderAmountQueryResult = $mysqli->query($pastWeekOrderAmountQuery);
        if (!$pastWeekOrderAmountQueryResult) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); "; 
            exit;
        }
        $soldThisPastWeek = $pastWeekOrderAmountQueryResult -> fetch_assoc();
        echo '<td>' . $soldThisPastWeek["sum"] . '</td>';
        
        // Get all products from orders within the past month
        $lastmonth = new DateTime('-1 month');
        $lastmonth = $lastmonth->format('Y-m-d H:i:s');
        $pastMonthOrderAmountQuery = "SELECT SUM(quantity) as sum FROM Orders 
            WHERE prodID='$productID' AND status!='Canceled' AND 
            order_datetime>='$lastmonth' GROUP BY prodID;";
        $pastMonthOrderAmountQueryResult = $mysqli->query($pastMonthOrderAmountQuery);
        if (!$pastMonthOrderAmountQueryResult) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); "; 
            exit;
        }
        $soldThisPastMonth = $pastMonthOrderAmountQueryResult -> fetch_assoc();
        echo '<td>' . $soldThisPastMonth["sum"] . '</td>';

        // Get all products from orders within the past year
        $lastyear = new DateTime('-1 year');
        $lastyear = $lastyear->format('Y-m-d H:i:s');
        $pastYearOrderAmountQuery = "SELECT SUM(quantity) as sum FROM Orders 
            WHERE prodID='$productID' AND status!='Canceled' AND 
            order_datetime>='$lastyear' GROUP BY prodID;";
        $pastYearOrderAmountQueryResult = $mysqli->query($pastYearOrderAmountQuery);
        if (!$pastYearOrderAmountQueryResult) {
            echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
            echo "Please try again later. Click 'OK' to go back.\"); "; 
            exit;
        }
        $soldThisPastYear = $pastYearOrderAmountQueryResult -> fetch_assoc();
        echo '<td>' . $soldThisPastYear["sum"] . '</td>';
    }
    echo '</table>';
    $mysqli->close();
?>
</div>

</body>
</html>
