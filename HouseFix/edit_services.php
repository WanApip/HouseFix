<?php
session_start();
include('dbconn.php');

if (!isset($_SESSION['technician_id'])) {
    header("Location: tech_login.php");
    exit();
}

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
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HouseFix | Manage Specialties</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding: 0; 
            padding-top: 80px; /* Add padding to prevent content from hiding behind fixed header */
        }
        
         .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #ddd; 
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .header-logo {
            height: 60px; /* Adjust this value to make it the right size */
            width: auto;   /* Maintains aspect ratio */
            display: block;
        }

        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        
        .btn-back {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-back:hover {
            background-color: #0056b3;
        }
        
        .empty-space {
            width: 80px;
        }
        
        .container { 
            max-width: 500px; 
            margin: 50px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        
        h2 { margin-top: 0; }
        input[type="checkbox"] { margin: 10px 5px; }
        .btn { width: 100%; padding: 12px; background-color: #734d26; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 20px;}
        .btn:hover { background-color: #5a3b1e;}
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <form action="tech_dashboard.php" style="margin: 0;">
         <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="nav-links">
        <a href="tech_login.php" style="color: #f8eed3;"></a>
    </div>
</div>
<div class="container">
    <h2>Select Your Service Categories</h2>
    <form method="POST">
        <?php
        $all_services = $conn->query("SELECT * FROM SERVICES");
        while($s = $all_services->fetch_assoc()) {
            // Check if technician already has this service
            $checked = $conn->query("SELECT * FROM TECHNICIAN_SERVICES WHERE technician_id = $tech_id AND services_id = {$s['services_id']}");
            $is_checked = ($checked->num_rows > 0) ? "checked" : "";
            
            echo "<input type='checkbox' name='services[]' value='{$s['services_id']}' $is_checked> 
                  <label>{$s['services_name']}</label><br>";
        }
        ?>
        <button type="submit" class="btn">Update Specialties</button>
    </form>
</div>
<br><br><br><br><br><br><br>
<footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>