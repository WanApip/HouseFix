<?php
session_start();
include('dbconn.php');

// Check if admin is logged in (optional - add your own admin login check)
 if (!isset($_SESSION['admin_id'])) {
   header("Location: admin_login.php");
    exit();
 }

// Premium Subscription Revenue
$prem_query = $conn->query("SELECT COUNT(*) as count FROM TECHNICIAN WHERE plan_type = 'Premium'");
$prem_count = $prem_query->fetch_assoc()['count'];
$premium_revenue = $prem_count * 99.00; 

// Web Usage Revenue: RM 5 * Total Bookings 
$booking_count = $conn->query("SELECT COUNT(*) as count FROM BOOKING")->fetch_assoc()['count'];
$web_usage_revenue = $booking_count * 5.00;

// Service Demand Data
$demand_query = $conn->query("SELECT S.services_name, COUNT(BD.booking_id) as demand 
                              FROM BOOKING_DETAIL BD 
                              JOIN SERVICES S ON BD.services_id = S.services_id 
                              GROUP BY S.services_id");
$services = [];
$counts = [];
while ($row = $demand_query->fetch_assoc()) {
    $services[] = $row['services_name'];
    $counts[] = $row['demand'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistics | HouseFix Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* Revenue Summary */
        .revenue-summary {
            background: linear-gradient(135deg, #734d26, #5a3b1e);
            color: #f8eed3;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .revenue-summary h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #f8eed3;
        }
        
        .revenue-summary .total {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            color: #ffd700;
        }
        
        /* Card */
        .card { 
            background: #fbf7e9; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            text-align: center;
        }
        
        .chart-container { 
            width: 400px; 
            margin: 20px; 
            display: inline-block; 
            vertical-align: top; 
        }
        
        .chart-container h3 {
            color: #734d26;
            margin-bottom: 15px;
        }
        
        .charts-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
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
        <br><h1>Platform Statistics</h1>
        <p>View revenue breakdown and service demand analytics</p>
    </div>

    <!-- Revenue Summary -->
    <div class="revenue-summary">
        <h3>Total Revenue</h3>
        <p class="total">RM <?php echo number_format($premium_revenue + $web_usage_revenue, 2); ?></p>
        <p style="margin: 5px 0 0 0; font-size: 14px;">
            Premium Subscriptions: RM <?php echo number_format($premium_revenue, 2); ?> | 
            Web Usage Fee: RM <?php echo number_format($web_usage_revenue, 2); ?>
        </p>
    </div>

    <!-- Charts Card -->
    <div class="card">
        <div class="charts-wrapper">
            <div class="chart-container">
                <h3>Revenue Breakdown (RM)</h3>
                <canvas id="revenueChart"></canvas>
            </div>

            <div class="chart-container">
                <h3>Service Demand</h3>
                <canvas id="demandChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'pie',
        data: {
            labels: ['Premium Subscription', 'Web Usage Fee'],
            datasets: [{ 
                data: [<?php echo $premium_revenue; ?>, <?php echo $web_usage_revenue; ?>], 
                backgroundColor: ['#734d26', '#ffd700'] 
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('demandChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($services); ?>,
            datasets: [{ 
                data: <?php echo json_encode($counts); ?>, 
                backgroundColor: ['#734d26', '#ffd700', '#e8dcc8', '#5a3b1e', '#c9cbcf', '#36a2eb', '#ffce56'] 
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
<footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>