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
    echo "window.location.href='../../../index.html'; </script>"; 
    exit();
}
// get user firstname and lastname and store in current session
else {
    $query = "SELECT firstname, lastname FROM Users WHERE userID='$userID'";
    $result = $mysqli->query($query);
    if (!$result) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); </script>"; 
        exit();
    }
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $firstname = $row["firstname"];
    $lastname = $row["lastname"];

    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
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
        <a href="./homepage.php" class="active">Home</a>
        <a href="./inventory.php">Inventory</a>
        <a href="./promotions.php">Promotions</a>
        <a href="./orders.php">Orders</a>
        <a href="./statistics.php">Statistics</a>
        <a href="javascript:void(0);" onclick="logout()">Logout</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
</div>

<div class="imgcontainer">
    <h1>ToyzRUs Manager Homepage</h1>
    <h2>Welcome, <?php echo $firstname . " " . $lastname ?>!</h2>
    <p> Please select one of the links above <br> 
        to start managing inventory and orders!</p> 
    <img src="../../assets/managerhomepagelogo.png" alt="Avatar" class="avatar">
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
</body>
</html>
