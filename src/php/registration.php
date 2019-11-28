<?php
// extract POST variables from form submission
$username = $_POST["username"];
$password = $_POST["password"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];

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
    echo "window.location.href='../../../index.html' </script>"; 
    exit;
}
else {
    // Get all users with a particular username
    $query = "SELECT * FROM Users WHERE username = '$username'";
    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if (!$result) {
        echo "<script> alert(\"Query failed: " . $mysqli->error . ". ";
        echo "Please try again later. Click 'OK' to go back.\"); "; 
        echo "window.location.href='../html/registration.html' </script>";
        exit;
    }
    // If username does not exist, add new user to database
    else if ($result->num_rows === 0) {
        $insertUser = "INSERT INTO Users(username,password,firstname,lastname,isStaff,isManager) VALUES('$username','$password','$firstname','$lastname',FALSE,FALSE)";
        $result = $mysqli->query($insertUser);
        if (!$result) {
            echo "<script> alert(\"Registration failed: " . $mysqli->error . ". "; 
            echo "Please try again later. Click 'OK' to go back.\"); "; 
            echo "window.location.href='../html/registration.html' </script>";
            exit;
        }
        else {
            echo "<script> alert(\"Registration successful! Click 'OK' to return to login page.\"); ";
            echo "window.location.href='../../index.html' </script>";
        }
    }
    // Username already exists, prompt user to enter another username to register
    else {
        echo "<script> alert(\"The username " . $username . " already exists! ";
        echo "Please choose another username.\"); "; 
        echo "window.location.href='../html/registration.html' </script>";
    }
}
