<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 
// Total unique sessions
$total_sessions_sql = "SELECT COUNT(DISTINCT ip_address) AS total FROM user_logs WHERE DATE(access_time) = CURDATE()";
$total_sessions_result = $conn->query($total_sessions_sql);
$total_sessions = $total_sessions_result->fetch_assoc()['total'];

// Today's unique sessions
$today_sql = "SELECT COUNT(DISTINCT ip_address) AS today FROM user_logs WHERE DATE(access_time) = CURDATE()";
$today_result = $conn->query($today_sql);
$today_sessions = $today_result->fetch_assoc()['today'];

// Yesterday's unique sessions
$yesterday_sql = "SELECT COUNT(DISTINCT ip_address) AS yesterday FROM user_logs WHERE DATE(access_time) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
$yesterday_result = $conn->query($yesterday_sql);
$yesterday_sessions = $yesterday_result->fetch_assoc()['yesterday'];

// Percentage change
$percentage_change = $yesterday_sessions > 0 ? (($today_sessions - $yesterday_sessions) / $yesterday_sessions) * 100 : 0;
$percentage_change = round($percentage_change, 2);

// Set up response
$data = [
    'total_sessions' => $total_sessions,
    'today_sessions' => $today_sessions,
    'yesterday_sessions' => $yesterday_sessions,
    'percentage_change' => $percentage_change
];

echo json_encode($data);
?>