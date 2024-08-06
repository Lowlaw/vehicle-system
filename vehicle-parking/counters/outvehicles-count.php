<?php

    include './includes/dbconn.php';
    date_default_timezone_set('Asia/Manila'); // Set the default timezone

    $query=mysqli_query($con,"SELECT ID from  vehicle_info where Status='Out'");
    $count_parkings=mysqli_num_rows($query);

    echo $count_parkings
 ?>