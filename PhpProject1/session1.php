<?php

// For change password functionality
include('config.php');
session_start();

//Holds username to be changed
$user_check = $_SESSION['userPasswordChange'];

if (!isset($_SESSION['userPasswordChange'])) {
    header("location:Homepage.php");
}


?>