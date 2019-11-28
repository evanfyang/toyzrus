<?php
// start session
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
    echo '<script> alert("Could not connect to database';
    echo 'Error: ' . $mysqli->connect_error . '. ';
    echo 'Please try again another time."); '; 
    echo 'window.location.href="../../index.html"'; 
    exit;
}
else {
    // Get userID and store in session
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
        echo '<script> alert("Query failed: ' . $mysqli->error . '. ';
        echo 'Please try again later. Click \'OK\' to go back.");';
        echo 'window.location.href=../../index.html </script>';
        exit;
    }
    // incorrect username or password
    else if ($result->num_rows == 0) {
        echo "<script> alert('Login failed: incorrect username or password. ";
        echo "Please try again or register as a new user. ";
        echo "Click \'OK\' to return to the login page.'); ";
        echo "window.location.href='../../index.html'</script>";
    }
    // user is staff
    else if ($row["isStaff"] && !$row["isManager"]) {
        // go to staff page
        echo "<p>Login successful. Welcome, Staff.</p>";
    }
    // user is manager
    else if ($row["isStaff"] && $row["isManager"]) {
        // login successful, go to manager page
        header("Location: ./staff/homepage.php");
        exit;
    }
    // regular user
    else {
        // login successful, go to normal homepage
        header("Location: ./customer/homepage.php");
        exit;
    }
}
$mysqli->close();
?>
