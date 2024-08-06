<?php
    session_start();
    error_reporting(0);
    include('includes/dbconn.php');
    
    date_default_timezone_set('Asia/Manila'); // Set the default timezone

    if (strlen($_SESSION['vpmsaid'])==0) {
        header('location:logout.php');
    } else {
        if(isset($_POST['submit-in'])) {
            $cid = $_GET['updateid'];
            $remark = $_POST['remark'];
            $status = $_POST['status'];
            $parkingcharge = $_POST['parkingcharge'];

            // Update the vehicle info with the calculated parking charge
            $query = mysqli_query($con, "UPDATE vehicle_info SET Remark='$remark', Status='$status', ParkingCharge='$parkingcharge' WHERE ID='$cid'");
            if ($query) {
                $msg = "All remarks have been updated.";
            } else {
                $msg = "Something Went Wrong";
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VPS</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/datatable.css" rel="stylesheet">
    <link href="css/datepicker3.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <!-- Custom Font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navigation.php'; ?>
    <?php $page="in-vehicle"; include 'includes/sidebar.php'; ?>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="dashboard.php">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">Vehicle Category Management</li>
            </ol>
        </div><!--/.row-->
        
        <div class="row">
            <div class="col-lg-12">
                <!-- <h1 class="page-header">Vehicle Management</h1> -->
            </div>
        </div><!--/.row-->
        
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Incoming Vehicles</div>
                    <div class="panel-body">
                        <?php if(isset($msg)) {
                            echo "<div class='alert bg-info ' role='alert'>
                            <em class='fa fa-lg fa-warning'>&nbsp;</em> 
                            $msg
                            <a href='#' class='pull-right'>
                            <em class='fa fa-lg fa-close'>
                            </em></a></div>";
                        } ?>

                        <div class="col-md-12">
                            <form method="POST">
                                <?php
                                $cid = $_GET['updateid'];
                                $ret = mysqli_query($con, "SELECT * FROM vehicle_info WHERE ID='$cid'");
                                while ($row = mysqli_fetch_array($ret)) {
                                    $inTime = new DateTime($row['InTime']);
                                    $currentTime = new DateTime(); // Current time

                                    $interval = $inTime->diff($currentTime);
                                    $hours = ($interval->days * 24) + $interval->h + ($interval->i / 60); // Total hours

                                    $category = $row['VehicleCategory'];
                                    $minCharge = ($category == "Four Wheeler") ? 100 : 50;

                                    $rateQuery = mysqli_query($con, "SELECT HourlyRate FROM vcategory WHERE VehicleCat='$category'");
                                    $rateRow = mysqli_fetch_array($rateQuery);
                                    $hourlyRate = $rateRow['HourlyRate'];

                                    // Calculate total charge based on hours
                                    $parkingCharge = $hourlyRate * $hours;

                                    // Ensure minimum charge
                                    if ($parkingCharge < $minCharge) {
                                        $parkingCharge = $minCharge;
                                    }
                                ?>
                                
                                <div class="form-group">
                                    <label>Vehicle Registration Number</label>
                                    <input type="text" class="form-control" value="<?php echo $row['RegistrationNumber']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" value="<?php echo $row['VehicleCompanyname']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Category</label>
                                    <input type="text" class="form-control" value="<?php echo $row['VehicleCategory']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Parking Number</label>
                                    <input type="text" class="form-control" value="<?php echo $row['ParkingNumber']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Vehicle IN Time</label>
                                    <input type="text" class="form-control" value="<?php echo $row['InTime']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Vehicle Owned By</label>
                                    <input type="text" class="form-control" value="<?php echo $row['OwnerName']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Vehicle Owner Contact</label>
                                    <input type="text" class="form-control" value="<?php echo $row['OwnerContactNumber']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Current Status</label>
                                    <input type="text" class="form-control" value="<?php echo ($row['Status'] == "" ? "Vehicle In" : "Vehicle Out"); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Hours Parked</label>
                                    <input type="text" class="form-control" value="<?php echo number_format($hours, 2); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Total Charge</label>
                                    <input type="text" class="form-control" value="PHP <?php echo number_format($parkingCharge, 2); ?>" readonly>
                                    <!-- Hidden input to pass the parking charge to the form submission -->
                                    <input type="hidden" name="parkingcharge" value="<?php echo $parkingCharge; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control" required="true">
                                        <option value="Out">Outgoing Vehicle</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Remarks</label>
                                    <input type="text" class="form-control" placeholder="" id="remark" name="remark" required>
                                </div>
                                
                                <?php } ?>
                                <button type="submit" class="btn btn-success" name="submit-in">Submit For Out-Going</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </form>
                        </div><!-- col-md-12 ends -->
                    </div>
                </div>
            </div>
        </div><!--/.row-->

        <?php include 'includes/footer.php'; ?>
    </div><!--/.main-->

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/chart.min.js"></script>
    <script src="js/chart-data.js"></script>
    <script src="js/easypiechart.js"></script>
    <script src="js/easypiechart-data.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/custom.js"></script>
    <script>
        window.onload = function () {
            var chart1 = document.getElementById("line-chart").getContext("2d");
            window.myLine = new Chart(chart1).Line(lineChartData, {
                responsive: true,
                scaleLineColor: "rgba(0,0,0,.2)",
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleFontColor: "#c5c7cc"
            });
        };
    </script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>
</html>
