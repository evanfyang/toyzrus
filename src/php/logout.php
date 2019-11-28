<?php
// start session   
session_start();
if(!isset($_SESSION['username'])) {
    // not logged in
    header('Location: ../../index.html');
    exit();
}
// destroy current session
session_destroy();
header("Location: ../../index.html");
exit();
?>
