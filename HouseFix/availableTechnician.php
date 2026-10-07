<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('dbconn.php');

// Capture the service ID from the user and the area ID
$area_id = isset($_POST['area_id']) ? $_POST['area_id'] : '';
$selected_service = isset($_POST['selected_service']) ? $_POST['selected_service'] : '';

$_SESSION['booking_area_id'] = $area_id;
$_SESSION['booking_service_id'] = $selected_service;

$technicians = [];

if (!empty($area_id) && !empty($selected_service)) {
    
    $query = "SELECT DISTINCT t.* FROM TECHNICIAN t 
              JOIN TECHNICIAN_SERVICES ts ON t.technician_id = ts.technician_id 
              WHERE t.area_id = $area_id 
              AND ts.services_id = $selected_service 
              AND t.tech_availability = 'Available'";
              
    $result = $conn->query($query);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $technicians[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Available Technicians</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #f8eed3;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            height: 60px;
            width: auto;
            display: block;
        }
        
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #f8eed3; }
        
        .container { 
            padding: 40px; 
            max-width: 800px; 
            margin: 0 auto; 
            flex: 1;
            width: 100%;
        }
        
        .section-title { font-size: 28px; margin-bottom: 10px; text-align: center; color: #333; }
        .section-subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .tech-list { display: flex; flex-direction: column; gap: 20px; }
        .tech-card { border: 1px solid #ccc; border-radius: 8px; padding: 20px; background-color: #fbf7e9; display: flex; justify-content: space-between; align-items: center; position: relative; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .tech-card.premium-card { border: 2px solid #ffd700; background-color: #fbf7e9; }
        .tech-info h3 { margin: 0 0 5px 0; color: #333; display: flex; align-items: center; gap: 10px; }
        .tech-info p { margin: 3px 0; color: #666; font-size: 14px; }
        .status-badge { background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .premium-badge { background-color: #ffd700; color: #333; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; border: 1px solid #e6c200; }
        .btn-book { background-color: #734d26; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; text-decoration: none; }
        .btn-book:hover { background-color: #5a3b1e; }
        .no-results { text-align: center; padding: 40px; border: 1px dashed #ccc; border-radius: 8px; background: #fbf7e9; }
        .btn-back { display: inline-block; margin-top: 15px; color: #734d26; text-decoration: none; font-weight: bold; }
        .btn-back:hover { text-decoration: underline; }
        
        .footer { 
            background-color: #734d26; 
            color: #f8eed3; 
            text-align: center; 
            padding: 20px; 
            font-size: 14px; 
            margin-top: auto;
        }
    </style>
</head>
<body>

<!-- FIXED HEADER - Stays on top when scrolling -->
<div class="header">
    <form action="services.php" style="margin: 0;">
       <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="nav-links">
        <a href="tech_login.php"></a>
    </div>
</div>

<div class="container">
    <br><br><h2 class="section-title">Available Technicians</h2>
    <p class="section-subtitle">Showing local experts available to fix your problem right now.</p>

    <div class="tech-list">
        <?php 
        $displayed_techs = 0;

        if (!empty($technicians)) {
            foreach ($technicians as $tech) { 
                $current_tech_id = $tech['technician_id'];

                // Count total bookings for this technician in the current month and year
                $count_query = "SELECT COUNT(*) as current_month_jobs 
                                FROM booking 
                                WHERE technician_id = $current_tech_id 
                                AND MONTH(booking_date) = MONTH(CURRENT_DATE()) 
                                AND YEAR(booking_date) = YEAR(CURRENT_DATE())";
                
                $count_result = $conn->query($count_query);
                $job_data = $count_result ? $count_result->fetch_assoc() : ['current_month_jobs' => 0];
                $monthly_jobs = $job_data['current_month_jobs'];

                // Enforce basic user limit rule
                $is_basic = ($tech['plan_type'] == 'Basic');
                if ($is_basic && $monthly_jobs >= 10) {
                    // Skip rendering this technician since they reached the limit
                    continue;
                }

                // Increment the counter for actually displayed technicians
                $displayed_techs++;
                
                // Add a special CSS class for premium cards
                $card_class = ($tech['plan_type'] == 'Premium') ? 'tech-card premium-card' : 'tech-card';
                ?>
                <div class="<?php echo $card_class; ?>">
                    <div class="tech-info">
                        <h3>
                            <?php echo htmlspecialchars($tech['tech_name']); ?>
                            <?php if ($tech['plan_type'] == 'Premium'): ?>
                                <span class="premium-badge">⭐ PREMIUM</span>
                            <?php endif; ?>
                        </h3>
                        <p>Contact: <?php echo htmlspecialchars($tech['tech_phonenum']); ?></p>
                        <p>Email: <?php echo htmlspecialchars($tech['tech_email']); ?></p>
                        <p>Status: <span class="status-badge"><?php echo htmlspecialchars($tech['tech_availability']); ?></span></p>
                    </div>
                    <div>
                        <form action="booking.php" method="POST">
                            <input type="hidden" name="technician_id" value="<?php echo $tech['technician_id']; ?>">
                            <button type="submit" class="btn-book">Book Technician</button>
                        </form>
                    </div>
                </div>
            <?php 
            } 
        } 

        // If no technicians passed the visibility filter or list was empty
        if ($displayed_techs === 0) { 
        ?>
            <div class="no-results">
                <h3>No matching technicians found in your area.</h3>
                <p>Try selecting a different service or location area.</p>
                <a href="services.php" class="btn-back">← Go Back to Selection</a>
            </div>
        <?php 
        } 
        ?>
    </div>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>

</body>
</html>