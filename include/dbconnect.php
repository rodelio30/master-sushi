<?php
session_start();
// header('location: 401.html');

if(!defined('MSmember')){
    // header('location: index.php');
    header('location: ../401.html');
    die();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "master_sushi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>