<?php
include('dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Sanitize all inputs
    $name = $conn->real_escape_string($_POST['tech_name']);
    $email = $conn->real_escape_string($_POST['tech_email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $conn->real_escape_string($_POST['tech_phonenum']);
    $ssm = $conn->real_escape_string($_POST['ssm_num']);
    
    // Captured from your new dropdowns
    $area_id = intval($_POST['area_id']);
    $services_id = intval($_POST['services_id']);
 
    // 2. Insert into TECHNICIAN table with area_id
    $sql_tech = "INSERT INTO TECHNICIAN (tech_name, tech_email, password_hash, tech_phonenum, ssm_num, area_id, plan_type, tech_status, tech_availability) 
                 VALUES ('$name', '$email', '$pass', '$phone', '$ssm', $area_id, 'Basic', 'Pending', 'Unavailable')";

    if ($conn->query($sql_tech)) {
        // 3. Get the ID of the newly created technician
        $tech_id = $conn->insert_id; 

        // 4. Link to the selected service in the TECHNICIAN_SERVICES bridge table
        $sql_bridge = "INSERT INTO TECHNICIAN_SERVICES (technician_id, services_id) 
                       VALUES ($tech_id, $services_id)";
        
        if ($conn->query($sql_bridge)) {
            echo "Application submitted! Wait for Admin approval.";
        } else {
            echo "Error linking service: " . $conn->error;
        }
    } else {
        echo "Error creating account: " . $conn->error;
    }
}
?>