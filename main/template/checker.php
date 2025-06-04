<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

$global_user_role = $_SESSION['user_role'];
if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}
if ($_SESSION['user_role'] === 'Customer') {
    header("Location: ../customer/index.php");
    exit;
} 
?>  