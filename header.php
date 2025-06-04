<?php
define('MSmember', true); 
require('include/dbconnect.php'); 

if (!empty($_SESSION['user_id'])) {
    header("location: main/index.php");
    exit;
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="icon" type="image/jpg" sizes="16x16" href="assets/img/master_sushi_circle.png">
        <meta name="description" content="Master Sushi 2025" />
        <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; style-src 'self';"> -->

        <!-- <link rel="icon" type="image/jpg" sizes="16x16" href="assets/img/master_sushi_circle.png"> -->
        <!-- <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/master_sushi_circle.png"> -->
        <!-- <link rel="icon" type="image/png" sizes="16x16" href="assets/img/bg-cta.png"> -->
        <meta name="author" content="Master Sushi 2025" />
        <!-- <link rel="icon" type="image/jpg" sizes="16x16" href="/assets/img/master_sushi.jpg"> -->
        <title>Master Sushi</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/public_custom.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- <link href="https://fonts.cdnfonts.com/css/luckiest-guy" rel="stylesheet"> -->
</head>