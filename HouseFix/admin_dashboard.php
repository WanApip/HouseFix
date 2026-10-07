<?php
session_start();
include('dbconn.php');

// Check if admin is logged in (optional - add your own admin login check)
 if (!isset($_SESSION['admin_id'])) {
   header("Location: admin_login.php");
    exit();
 }

// Get statistics
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM BOOKING")->fetch_assoc();
$total_technicians = $conn->query("SELECT COUNT(*) as count FROM TECHNICIAN")->fetch_assoc();
$pending_techs = $conn->query("SELECT COUNT(*) as count FROM TECHNICIAN WHERE tech_status = 'Pending'")->fetch_assoc();
$total_customers = $conn->query("SELECT COUNT(*) as count FROM CUSTOMERS")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>HouseFix | Admin Dashboard</title>
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
        
        /* Dashboard Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            color: #734d26;
            margin-bottom: 30px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fbf7e9;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #734d26;
            font-size: 16px;
        }
        
        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #734d26;
            margin: 0;
        }
        
        /* Menu Cards */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .menu-card {
            background: #fbf7e9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            display: block;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .menu-card h3 {
            margin: 0 0 10px 0;
            color: #734d26;
            font-size: 20px;
        }
        
        .menu-card p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .menu-card.pending {
            border-left: 4px solid #ffc107;
        }
        
        .menu-card.technician {
            border-left: 4px solid #17a2b8;
        }
        
        .menu-card.statistics {
            border-left: 4px solid #28a745;
        }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<!-- Fixed Header with Back button and HouseFix center -->
<div class="header">
    <form action="admin_logout.php" style="margin: 0;">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Logout</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="empty-space"></div>
</div>

<div class="dashboard-container">
    <h1>Admin Overview</h1>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Bookings</h3>
            <p class="number"><?php echo $total_bookings['count']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Technicians</h3>
            <p class="number"><?php echo $total_technicians['count']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Approvals</h3>
            <p class="number" style="color: #ffc107;"><?php echo $pending_techs['count']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Customers</h3>
            <p class="number"><?php echo $total_customers['count']; ?></p>
        </div>
    </div>
    
    <!-- Navigation Menu Cards -->
    <div class="menu-grid">
        <a href="manage_techs.php" class="menu-card pending">
            <h3>Manage Technicians</h3>
            <p>Approve or reject technician registrations</p>
        </a>
        
        <a href="view_tech.php" class="menu-card technician">
            <h3>Services Directory</h3>
            <p>View all approved technicians by service</p>
        </a>
        
        <a href="statistic.php" class="menu-card statistics">
            <h3>Platform Statistics</h3>
            <p>View revenue and service demand charts</p>
        </a>
    </div>
</div>
<br><br><br><br><br><br><br><br><br>
<footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>