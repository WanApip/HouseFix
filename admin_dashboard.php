<?php
session_start();
include('dbconn.php');


?>

<!DOCTYPE html>
<html>
<head>
    <title>HouseFix | Admin Dashboard</title>
    <style>
        .wrapper { display: grid; grid-template-columns: 200px 1fr; min-height: 100vh; }
        .sidebar { background: #333; color: #fff; padding: 20px; }
        .content { padding: 20px; background: #f4f7f6; }
        .stat-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h3>HouseFix Admin</h3>
            <nav>
                <p><a href="admin_dashboard.php" style="color:#fff;">Dashboard</a></p>
                <p><a href="manage_techs.php" style="color:#fff;">Pending Technician</a></p>
                <p><a href="view_tech.php" style="color:#fff;">View Technician</a></p>
                <p><a href="statistic.php" style="color:#fff;">Platform Statistics</a></p>
            </nav>
        </div>
        <div class="content">
            <h1>Admin Overview</h1>
            <div class="stat-card">
                <h3>System Summary</h3>
                <?php 
                $total = $conn->query("SELECT COUNT(*) as count FROM BOOKING")->fetch_assoc();
                echo "<p>Total Bookings Processed: <strong>" . $total['count'] . "</strong></p>";
                ?>
            </div>
        </div>
    </div>
</body>
</html>