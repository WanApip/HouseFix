<?php
session_start();
include('dbconn.php');

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
    $tech_id = $_SESSION['technician_id'];

    // Update status to 'Completed'
    $sql = "UPDATE BOOKING SET booking_status = 'Completed' 
            WHERE booking_id = $booking_id AND technician_id = $tech_id";

    if ($conn->query($sql)) {
        header("Location: tech_dashboard.php?msg=JobCompleted");
    }
}
?>