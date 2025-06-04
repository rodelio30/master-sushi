<?php
// update_shop_status.php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

$status = $_POST['status'];
mysqli_query($conn, "UPDATE shop_status SET status = '$status' WHERE id = 1");
echo 'success';
?>