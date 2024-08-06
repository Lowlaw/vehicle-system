<?php
session_start();
include('includes/dbconn.php');

if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        // Delete the record
        $sql = "DELETE FROM vehicle_info WHERE ID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // Redirect to the main page after deletion
        header('location: out-vehicles.php');
    }
}
?>