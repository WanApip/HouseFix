<?php
session_start();
include('dbconn.php');



$tech_id = $_SESSION['technician_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Remove all existing services
    $conn->query("DELETE FROM TECHNICIAN_SERVICES WHERE technician_id = $tech_id");
    
    // 2. Add selected services
    if (!empty($_POST['services'])) {
        foreach ($_POST['services'] as $services_id) {
            $services_id = intval($services_id);
            $conn->query("INSERT INTO TECHNICIAN_SERVICES (technician_id, services_id) VALUES ($tech_id, $services_id)");
        }
    }
    header("Location: tech_dashboard.php?msg=ServicesUpdated");
}
?>

<!DOCTYPE html>
<html>
<head><title>Manage Specialties</title></head>
<body>
    <h2>Select Your Service Categories</h2>
    <form method="POST">
        <?php
        $all_services = $conn->query("SELECT * FROM SERVICES");
        while($s = $all_services->fetch_assoc()) {
            // Check if technician already has this service
            $checked = $conn->query("SELECT * FROM TECHNICIAN_SERVICES WHERE technician_id = $tech_id AND services_id = {$s['services_id']}");
            $is_checked = ($checked->num_rows > 0) ? "checked" : "";
            
            echo "<input type='checkbox' name='services[]' value='{$s['services_id']}' $is_checked> {$s['services_name']}<br>";
        }
        ?>
        <button type="submit">Update Specialties</button>
    </form>
</body>
</html>