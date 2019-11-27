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
    echo "Could not connect to database \n";
    echo "Error: ". $mysqli->connect_error . "\n";
    // please try again another time
    // go back to login button
    //header("Location: /url/to/the/other/page");
    exit;
}
else {
    $query = "SELECT * FROM Users WHERE username = '$username'";
    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if (!$result) {
        echo "Query failed: " . $mysqli->error . "\n";
        exit;
    }
    else if ($result->num_rows === 0) {
        // // find a new unique userID
        // $userIDQuery = "SELECT userID FROM Users";
        // $result = $mysqli->query($userIDQuery);
        // $userIDs = $result->fetch_array(MYSQLI_ASSOC);
        // $newUserID = rand();
		// $uniqueID = false;
        // while (!uniqueID) {
        //     $newUserID = rand();
        //     if (!in_array(newUserID, $userIDs)) {
        //         $uniqueID = true;
        //     }
        // }
        // insert new user
        $insertUser = "INSERT INTO Users(username,password,firstname,lastname,isStaff,isManager) VALUES('$username','$password','$firstname','$lastname',FALSE,FALSE)";
        $result = $mysqli->query($insertUser);
        if (!$result) {
            echo "<script> alert(\"Registration failed: " . $mysqli->error . ". Please try again later. Click 'OK' to return to registration page.\"); window.location.href='../html/registration.html' </script>";
            exit;
        }
        else {
            echo "<script> alert(\"Registration successful! Click 'OK' to return to login page.\"); window.location.href='../../index.html' </script>";
        }
    }
    else {
        echo "<script> alert(\"The username " . $username . " already exists! Please choose another username.\"); window.location.href='../html/registration.html' </script>";
        // go back to registration
        //header("Location: /url/to/the/other/page");
    }
}
