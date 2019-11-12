<?php
echo '<h1>Login</h1>';
// extract POST variables from form submission
$username = $_POST["username"];
$password = $_POST["password"];
$address = $_POST["address"];
$isRegistered = $_POST["isRegistered"];
// $isStaff = $_POST["isStaff"];
// $isManager = $_POST["isManager"];

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
  exit;
}
else {
  // FIXME: staff and manager logic
  // validate user login by querying form value
  $userQuery = "SELECT userID FROM Users WHERE username = '$username' AND password = '$password'";
  $q_result = $mysqli->query($userQuery);
  if (!$q_result) {
    echo "Query failed: " . $mysqli->error . "\n";
    exit;
  }
  else if ($q_result->num_rows === 0 && $isRegistered) {
    // could not find registered user
    echo "<p>Login failed. Retry or register to continue.</p>";
  }
  else if ($q_result->num_rows === 0 && !$isRegistered) {
    // could not find user and they are not registered, create new user
    // insert new user
    $insertUser = "INSERT INTO Users(username,address,password,isStaff,isManager) VALUES('$username','$address','$password',FALSE,FALSE)";
    $insertResult = $mysqli->query($insertUser);
    if (!$insertResult) {
      echo "User creation failed: " . $mysqli->error . "\n";
      exit;
    }
    echo "<p>Registration Successful.</p>";

    // show all registered users to confirm
    echo "<h5>All Registered Users:</h5>";
    $allUsersQuery = "SELECT username FROM Users";
    $all_result = $mysqli->query($allUsersQuery);
    // Execute the query and check for error
    if (!$all_result) {
      echo "Query failed: ". $mysqli->error. "\n";
      exit;
    }
    else if ($all_result->num_rows === 0) {
      // no registered users
      echo "<p>There are no registered users at this time.</p>";
    }
    else {
      // display each registered user
      while ($all_users = $all_result->fetch_assoc()) {
        // FIXME: display staff/manager info
        echo $all_users["username"] . "<br \>";
      }
    }
  }
  else if ($q_result->num_rows > 0 && !$isRegistered) {
    // registered user is trying to re-register instead of login
    echo "<p>The username: " . $username . " has already been registered. Please uncheck the registration box to login or try registering a new user</p>";
  }
  else {
    // registered user login was successful
    echo "<p>Login successful. Welcome, " . $username . ".</p>";
  }
}
?>