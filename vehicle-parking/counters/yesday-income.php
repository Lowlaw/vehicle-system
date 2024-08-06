<?php
include './includes/dbconn.php';
date_default_timezone_set('Asia/Manila'); // Set the default timezone

// Query to get the sum of parking charges for yesterday
$sql = "SELECT SUM(ParkingCharge) AS total FROM vehicle_info WHERE DATE(OutTime) = CURDATE() - INTERVAL 1 DAY";
$result = mysqli_query($con, $sql) or die(mysqli_error($con)); // Correctly use $con here

// Fetch the result
$row = mysqli_fetch_assoc($result);

// Output the total amount; use the alias 'total' from the SQL query
echo $row['total'] ? $row['total'] : '0';
?>
