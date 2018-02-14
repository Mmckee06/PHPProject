<?php

include('config.php');
session_start();

$user_check = $_SESSION['login_user'];

//Find user that has logged in
$ses_sql = $stmt = $db->prepare("select username from customer where username =?");
$stmt->bind_param("s", $_SESSION['login_user']);
$stmt->execute();

//$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$stmt->store_result();
$stmt->bind_result($username);

$login_session = $username;


$ses_sql1 = mysqli_query($db, "select * from customer where username = '$user_check' ");
$data = mysqli_fetch_array($ses_sql1);

$user_session = $data;

//Create sessions variable if they do not already exist

if (!isset($_SESSION['login_user'])) {
    header("location:Homepage.php");
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
    $_SESSION['cartQunatity'] = array();
    $_SESSION['cartPrice'] = array();
}

if (!isset($_SESSION['editUser'])) {
$_SESSION['editUser'] = '';
}

if (!isset($_SESSION['editItem'])) {
$_SESSION['editItem'] = '';
}

if (!isset($_SESSION['viewOrder'])) {
$_SESSION['viewOrder'] = '';
}

?>