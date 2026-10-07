<?php
session_start();
include('dbconn.php');

// Check if admin is logged in (optional - add your own admin login check)
 if (!isset($_SESSION['admin_id'])) {
   header("Location: admin_login.php");
    exit();
 }

// Fetch all services and their associated approved technicians with details
$services_query = $conn->query("SELECT S.services_id, S.services_name, 
                                T.technician_id, T.tech_name, T.photo_path
                                FROM SERVICES S
                                LEFT JOIN TECHNICIAN_SERVICES TS ON S.services_id = TS.services_id
                                LEFT JOIN TECHNICIAN T ON TS.technician_id = T.technician_id 
                                AND T.tech_status = 'Approved'
                                ORDER BY S.services_name, T.tech_name");
?>

<!DOCTYPE html>
<html>
<head>
    <title> HouseFix Admin| Services Directory</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding: 0; 
            padding-top: 80px;
        }
        
        /* Fixed Header */
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

        .logo { 
            font-size: 24px; 
            font-weight: bold; 
            color: #f8eed3; 
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .btn-back {
            background-color: #734d26;
            color: #f8eed3;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-back:hover {
            background-color: #5a3b1e;
        }
        
        .empty-space {
            width: 80px;
        }
        
        /* Container */
        .container { 
            max-width: 1200px; 
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Page Title */
        .page-title {
            margin-bottom: 30px;
        }
        
        .page-title h1 {
            font-size: 28px;
            color: #734d26;
            margin: 0;
        }
        
        .page-title p {
            color: #666;
            margin: 5px 0 0 0;
        }
        
        /* Service group */
        .service-group {
            margin-bottom: 30px;
            background: #fbf7e9;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .service-group h2 {
            color: #734d26;
            border-bottom: 3px solid #734d26;
            padding-bottom: 10px;
            margin-top: 0;
            font-size: 22px;
        }
        
        .tech-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        
        .tech-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            width: 120px;
            transition: all 0.2s;
            border: 1px solid #e0e0e0;
        }
        
        .tech-card:hover {
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            transform: scale(1.02);
        }
        
        .tech-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #734d26;
            margin-bottom: 10px;
            background-color: #e9ecef;
        }
        
        .tech-name {
            font-size: 14px;
            font-weight: bold;
            color: #734d26;
            margin: 0;
            word-wrap: break-word;
        }
        
        .no-tech {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
        
        .no-services {
            text-align: center; 
            padding: 50px; 
            background: #fbf7e9; 
            border-radius: 10px;
            color: #666;
        }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<!-- Fixed Header -->
<div class="header">
    <form action="admin_dashboard.php" style="margin: 0;">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="empty-space"></div>
</div>

<div class="container">
    <!-- Page Title -->
    <div class="page-title">
        <br><h1>Services Directory</h1>
        <p>View all technicians grouped by their service categories</p>
    </div>

    <?php 
    // Reorganize data by service
    $services_data = [];
    $services_query->data_seek(0);
    while ($row = $services_query->fetch_assoc()) {
        $service_id = $row['services_id'];
        $service_name = $row['services_name'];
        
        if (!isset($services_data[$service_id])) {
            $services_data[$service_id] = [
                'name' => $service_name,
                'technicians' => []
            ];
        }
        
        if ($row['technician_id']) {
            $services_data[$service_id]['technicians'][] = [
                'id' => $row['technician_id'],
                'name' => $row['tech_name'],
                'photo' => $row['photo_path']
            ];
        }
    }
    
    // Display each service
    if (!empty($services_data)):
        foreach ($services_data as $service): 
    ?>
        <div class="service-group">
            <h2><?php echo htmlspecialchars($service['name']); ?></h2>
            
            <?php if (!empty($service['technicians'])): ?>
                <div class="tech-wrapper">
                    <?php foreach ($service['technicians'] as $tech): ?>
                        <div class="tech-card">
                            <?php 
                            // Check if photo_path exists and file exists in uploads folder
                            if (!empty($tech['photo']) && file_exists($tech['photo'])): 
                            ?>
                                <img src="<?php echo htmlspecialchars($tech['photo']); ?>" alt="<?php echo htmlspecialchars($tech['name']); ?>" class="tech-photo">
                            <?php else: ?>
                                <img src="uploads/technician.png" alt="Default Avatar" class="tech-photo">
                            <?php endif; ?>
                            <p class="tech-name"><?php echo htmlspecialchars($tech['name']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-tech">No approved technicians for this service yet.</p>
            <?php endif; ?>
        </div>
    <?php 
        endforeach;
    else: 
    ?>
        <div class="no-services">
            <p>No services found.</p>
        </div>
    <?php endif; ?>
</div>
<footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>