<?php
// get_shop_status.php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

$result = mysqli_query($conn, "SELECT status FROM shop_status WHERE id = 1");
$row = mysqli_fetch_assoc($result);
echo $row['status'];
?>