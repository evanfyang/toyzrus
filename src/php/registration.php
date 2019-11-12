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
    $query = "SELECT * FROM Users WHERE username = '$username' AND password = '$password'";
    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if (!$result) {
        echo "Query failed: " . $mysqli->error . "\n";
        exit;
    }
    else if ($result->num_rows === 0) {
        // find a new unique userID
        $userIDQuery = "SELECT userID FROM Users";
        $result = $mysqli->query($userIDQuery);
        $userIDs = $result->fetch_array(MYSQLI_ASSOC);
        $uniqueID = false;
        while (!uniqueID) {
            $newUserID = rand();
            if (!in_array(newUserID, $userIDs)) {
                $uniqueID = true;
            }
        }
        // insert new user
        $insertUser = "INSERT INTO Users(userID,username,password,isStaff,isManager) VALUES('$newUserID','$username','$password',FALSE,FALSE)";
        $result = $mysqli->query($insertUser);
        if (!$result) {
            echo "Registration failed: " . $mysqli->error . "\n";
            exit;
        }
        else {
            echo "<p>Registration Successful.</p>";
        }
    }
    else {
        echo "<p> Username already exists. Please choose another username. </p>";
        // go back to registration
        //header("Location: /url/to/the/other/page");
    }
}