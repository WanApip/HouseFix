<?php
session_start();
include('dbconn.php');


// Fetch all services and their associated approved technicians
$services_query = $conn->query("SELECT S.services_id, S.services_name, 
                                GROUP_CONCAT(T.tech_name SEPARATOR ', ') as technician_names
                                FROM SERVICES S
                                LEFT JOIN TECHNICIAN_SERVICES TS ON S.services_id = TS.services_id
                                LEFT JOIN TECHNICIAN T ON TS.technician_id = T.technician_id 
                                AND T.tech_status = 'Approved'
                                GROUP BY S.services_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Directory | Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .container { display: flex; flex-wrap: wrap; gap: 20px; max-width: 1200px; margin: auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 300px; }
        h2 { color: #333; margin-top: 0; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .tech-list { font-size: 0.9em; color: #555; list-style: none; padding: 0; }
        .tech-item { background: #f8f9fa; margin: 5px 0; padding: 8px; border-radius: 4px; border-left: 3px solid #007bff; }
    </style>
</head>
<body>

<div style="max-width: 1200px; margin: auto; margin-bottom: 20px;">
    <h1>Technicians by Service</h1>
    <a href="admin_dashboard.php">← Back to Dashboard</a>
</div>

<div class="container">
    <?php while ($row = $services_query->fetch_assoc()): ?>
    <div class="card">
        <h2><?php echo htmlspecialchars($row['services_name']); ?></h2>
        
        <?php if ($row['technician_names']): ?>
            <ul class="tech-list">
                <?php 
                $techs = explode(', ', $row['technician_names']);
                foreach ($techs as $name): ?>
                    <li class="tech-item"><?php echo htmlspecialchars($name); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p style="color: #999; font-style: italic;">No approved techs for this service.</p>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>