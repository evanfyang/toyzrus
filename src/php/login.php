<?php
// extract POST variables from form submission
$username = $_POST["username"];
$password = $_POST["password"];

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
  // validate user login by querying form value
  $query = "SELECT isStaff, isManager FROM Users WHERE username = '$username' AND password = '$password'";
  $result = $mysqli->query($query);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  if (!$result) {
    echo "Query failed: " . $mysqli->error . "\n";
    exit;
  }
  // incorrect username or password
  else if ($result->num_rows === 0) {
    echo "<p>Login failed. Retry or register to continue.</p>";
    // go back to login button
    //header("Location: /url/to/the/other/page");
  }
  // user is staff
  else if (strtolower($row["isStaff"]) == "true"){
      // go to staff page
      echo "<p>Login successful. Welcome, staff.</p>";
  }
  // user is manager
  else if (strtolower($row["isStaff"]) == "true"){
      // go to manager page
      echo "<p>Login successful. Welcome, manager.</p>";
  }
  // regular user
  else {
    // registered user login was successful
    echo "<p>Login successful. Welcome, " . $username . ".</p>";
  }
}
?>