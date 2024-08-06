<?php
    include('includes/dbconn.php');
    
    if (isset($_POST['category'])) {
        $category = $_POST['category'];
        $query = mysqli_query($con, "SELECT HourlyRate FROM vcategory WHERE VehicleCat='$category'");
        $result = mysqli_fetch_array($query);
        echo $result['HourlyRate'];
    }
?>
