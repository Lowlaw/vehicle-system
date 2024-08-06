<?php
include './includes/dbconn.php';
date_default_timezone_set('Asia/Manila'); // Set the default timezone

// Query to get the sum of all parking charges
$sql = "SELECT SUM(ParkingCharge) AS total FROM vehicle_info";
$amountsum = mysqli_query($con, $sql) or die(mysqli_error($con)); // Correctly use $con here

// Fetch the result
$row_amountsum = mysqli_fetch_assoc($amountsum);

// Output the total amount; use the alias 'total' from the SQL query
echo $row_amountsum['total'] ? $row_amountsum['total'] : '0';
?>
