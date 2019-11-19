<?php
session_start();
// extract POST variables from form submission
$username = $_POST["username"];
$password = $_POST["password"];

// set session variables
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;

// connect to mysql
$host = "localhost";
$mysqlUser = "root";
$mysqlPassword = "pwd";
$mysqldb = "ecommerce";
$mysqli = new mysqli($host, $mysqlUser, $mysqlPassword, $mysqldb);

// check connection
if ($mysqli->connect_errno) {
  echo "Could not connect to database \n";
  echo "Error: ". $mysqli->connect_error . "\n";
  // please try again another time
  // go back to login button
  //header("Location: /url/to/the/other/page");
  exit;
}
else {

  $query = "SELECT userID FROM Users WHERE username = '$username' AND password = '$password'";
  $result = $mysqli->query($query);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $userID = $row["userID"];
  $_SESSION['userID'] = $userID;

  // validate user login by querying form value
  $query = "SELECT isStaff, isManager FROM Users WHERE username = '$username' AND password = '$password'";
  $result = $mysqli->query($query);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  if (!$result) {
    echo "Query failed: " . $mysqli->error . "\n";
    exit;
  }
  // incorrect username or password
  else if ($result->num_rows == 0) {
    echo "<p>Login failed. Retry or register to continue.</p>";
    // go back to login button
    //header("Location: /url/to/the/other/page");
  }
  // user is staff
  else if ($row["isStaff"] && !$row["isManager"]){
      // go to staff page
      echo "<p>Login successful. Welcome, Staff.</p>";
  }
  // user is manager
  else if ($row["isStaff"] && $row["isManager"]){
      // go to manager page
      echo "<p>Login successful. Welcome, Manager.</p>";
  }
  // regular user
  else {
    // login successful, go to normal homepage
    header("Location: ./customer_homepage.php");
    exit;
  }
}

$mysqli->close();
?>
