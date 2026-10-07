<?php
session_start();
include('dbconn.php');

if (!isset($_SESSION['technician_id'])) {
    header("Location: tech_login.php");
    exit();
}

$tech_id = $_SESSION['technician_id'];

// Get current status
$result = $conn->query("SELECT tech_availability FROM TECHNICIAN WHERE technician_id = $tech_id");
$row = $result->fetch_assoc();
$new_status = ($row['tech_availability'] == 'Available') ? 'Unavailable' : 'Available';

// Update status
$conn->query("UPDATE TECHNICIAN SET tech_availability = '$new_status' WHERE technician_id = $tech_id");

header("Location: tech_dashboard.php");
exit();
?>