<?php
session_start();
include('dbconn.php');




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
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .chart-container { width: 400px; margin: 20px; display: inline-block; vertical-align: top; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Platform Statistics</h1>
        
        <div class="chart-container">
            <h3>Web Revenue (RM)</h3>
            <canvas id="revenueChart"></canvas>
        </div>

        <div class="chart-container">
            <h3>Service Demand (Bookings)</h3>
            <canvas id="demandChart"></canvas>
        </div>
        
        <br>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>

    <script>
        new Chart(document.getElementById('revenueChart'), {
            type: 'pie',
            data: {
                labels: ['Premium Sub', 'Web Usage Fee'],
                datasets: [{ 
                    data: [<?php echo $premium_revenue; ?>, <?php echo $web_usage_revenue; ?>], 
                    backgroundColor: ['#36a2eb', '#ffce56', '#ff6384'] 
                }]
            }
        });

        new Chart(document.getElementById('demandChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($services); ?>,
                datasets: [{ 
                    data: <?php echo json_encode($counts); ?>, 
                    backgroundColor: ['#ff6384', '#4bc0c0', '#ff9f40', '#9966ff', '#c9cbcf'] 
                }]
            }
        });
    </script>
</body>
</html>