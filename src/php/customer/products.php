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
    exit;
}
else {
    // Get all products to display
    $query = "SELECT * FROM Products ORDER BY category ASC;";
    $result = $mysqli->query($query);
    if (!$result) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); </script>"; 
        exit;
    }
    $categoryQuery = "SELECT DISTINCT category FROM Products ORDER BY category ASC;";
    $categories = $mysqli->query($categoryQuery);
    if (!$categories) {
        echo "<script> alert(\"Query failed to retrieve categories: " . $mysqli->error . ", ";
        echo "Please try again later. Click 'OK' to go back.\"); </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/main.css" />
</head>
<body>

<div class="topnav" id="myTopnav">
    <div style="float:left">  
        <p style="float:left; color:#f2f2f2; text-align: center; text-decoration: none; 
        font-size: 15px; margin-left:10px; margin-bottom:0px"> ToyzRUs </p>
    </div>
    <div style="float:right">
        <a href="./homepage.php">Home</a>
        <a href="./products.php" class="active">Products</a>
        <a href="./orders.php">Orders</a>
        <a href="./shoppingcart.php">Shopping Cart</a>
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
    <h1>Products</h1>
    <img src="../../assets/ProductsLogo.png" alt="Avatar" class="avatar">
</div>

<div>
<?php
    //display filter
    echo '<table>';
    echo '<tr>';
    echo '<th> Filter: </th>';
    echo '<th> <select name="selectCat">';
    echo '<option value="All"> All </option>';
    while ($catRow = $categories->fetch_assoc()) {
        echo '<option value="' . $catRow['category'] . '"> ' . $catRow['category'] . '</option>';
    }
    echo '</select> </th>';
    echo '<th> <input type="text" name="productName"> </th>';
    if (isset($_POST['selectCat'])) {
        $selectedCat = $_POST['selectCat'];
    }
    if (isset($_POST['productName'])) {
        $productSearch = $_POST['productName'];
    } else {
        $productSearch = '';
    }

    // Display table header
    echo '<form action="./addtocart.php" method="POST">';
    echo '<table>';
    echo '<tr>';
    echo '<th> Product Name </th>';      
    echo '<th> Category </th>';
    echo '<th> Price </th>';
    echo '<th> Promo </th>';
    echo '<th> Stock </th>';
    echo '<th> Action </th>';
    echo '</tr>';
    // Add products into table
    while ($row = $result->fetch_assoc()) {
        if ($row['category'] == $selectedCat or $selectedCat == "All" ) {
            if ($row['name'] == $productSearch or $productSearch == "") {
                echo '<tr>';
                echo '<td>' . $row["name"] . '</td>';
                echo '<td>' . $row["category"] . '</td>';
                echo '<td>$' . $row["price"] . '</td>';
                echo '<td>' . $row["promotions"] . '%</td>';
                // Out of stock product, disable 'Add to Cart' button
                if ($row["inventory"] == 0) {
                    echo '<td><center><mark style="background-color:#F44336">';
                    echo 'Out of Stock</mark></center></td>';
                    echo '<td><center><button name="id" value="' . $row["productID"] . '"';
                    echo 'type="submit" onclick="addToCartAlert()" disabled> ';
                    echo 'Add to Cart </button></center></td>';
                }
                // Low stock
                else if ($row["inventory"] <= 5) {
                    echo '<td><center><mark style="background-color:#FFE158">';
                    echo 'Low Stock</mark></center></td>';
                    echo '<td><center><button name="id" value="' . $row["productID"] .'"';
                    echo 'type="submit" onclick="addToCartAlert()"> '; 
                    echo 'Add to Cart </button></center></td>';
                }
                // In stock
                else {
                    echo '<td><center><mark style="background-color:#4F7FE4">';
                    echo 'In Stock</mark></center></td>';
                    echo '<td><center><button name="id" value="' . $row["productID"] .'"';
                    echo 'type="submit" onclick="addToCartAlert()"> '; 
                    echo 'Add to Cart </button></center></td>';
                }
                echo '</tr>';
            }
        }
    }
    echo '</table>';
    echo '</form>';
    $mysqli->close();
?>
</div>
<script>
function addToCartAlert() {
    alert("The item was successfully added to your cart!");
}
</script>
</body>
</html>
