<?php
session_start();
include('dbconn.php');

if (!isset($_SESSION['technician_id'])) {
    header("Location: tech_login.php");
    exit();
}

$tech_id = $_SESSION['technician_id'];

// Fetch Technician Details
$tech_query = $conn->query("SELECT * FROM TECHNICIAN WHERE technician_id = $tech_id");
$tech = $tech_query->fetch_assoc();

// Fetch Monthly Job Usage
$month_jobs_query = $conn->query("SELECT COUNT(*) as total FROM BOOKING 
    WHERE technician_id = $tech_id 
    AND MONTH(booking_date) = MONTH(CURRENT_DATE()) 
    AND YEAR(booking_date) = YEAR(CURRENT_DATE())");
$month_jobs = $month_jobs_query->fetch_assoc()['total'];

// Set Logic for Basic Plan
$job_limit = 10;
$is_basic = ($tech['plan_type'] == 'Basic');
$limit_reached = ($is_basic && $month_jobs >= $job_limit);

// Fetch Services
$services_query = $conn->query("SELECT S.services_name 
                                FROM SERVICES S 
                                JOIN TECHNICIAN_SERVICES TS ON S.services_id = TS.services_id 
                                WHERE TS.technician_id = $tech_id");

// Fetch Customer Reviews
$reviews_query = $conn->query("SELECT R.*, C.customer_name 
                               FROM REVIEWS R 
                               JOIN BOOKING B ON R.booking_id = B.booking_id 
                               JOIN CUSTOMERS C ON B.customer_id = C.customer_id 
                               WHERE B.technician_id = $tech_id 
                               ORDER BY R.review_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>HouseFix | Technician Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .dashboard-container { max-width: 1000px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card { background: #fff; padding: 20px; border-radius: 10px; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .premium { border: 2px solid #ffd700; background: #fffdf0; }
        .btn { padding: 10px 15px; border-radius: 5px; text-decoration: none; color: #fff; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .service-tag { background: #e0e0e0; padding: 5px 10px; border-radius: 5px; margin-right: 5px; display: inline-block; }
        
        /* Review Section Styling */
        .review-item { border-bottom: 1px solid #eee; padding: 15px 0; }
        .review-item:last-child { border-bottom: none; }
        .stars { color: #ffd700; font-weight: bold; }
        .locked-comment { color: #888; font-style: italic; background: #eee; padding: 5px 10px; border-radius: 4px; display: inline-block; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($tech['tech_name']); ?></h1>
        <div>
            Status: <strong style="color: <?php echo ($tech['tech_availability'] == 'Available') ? 'green' : 'red'; ?>;">
                <?php echo $tech['tech_availability']; ?>
            </strong>
            <a href="toggle_availability.php" onClick="return confirm('Are you sure you want to change your status?');" class="btn" style="background: #333; margin-left: 10px;">Toggle</a>
        </div>
    </div>

    <div style="display: flex; gap: 20px;">
        <div class="card" style="flex: 1; border-left: 5px solid <?php echo $limit_reached ? '#dc3545' : '#28a745'; ?>;">
            <h3>Monthly Job Usage</h3>
            <p>You have used <strong><?php echo $month_jobs; ?> / <?php echo $is_basic ? $job_limit : 'Unlimited'; ?></strong> jobs this month.</p>
            <?php if ($limit_reached): ?>
                <p style="color: #dc3545; font-weight: bold;">⚠️ Basic plan limit reached. Upgrade to continue.</p>
            <?php endif; ?>
        </div>

        <div class="card" style="flex: 1;">
            <h3>Subscription Plan</h3>
            <p><?php echo strtoupper($tech['plan_type']); ?> PLAN 
            <?php if ($tech['plan_type'] == 'Premium'): ?> <span style="color: #ffd700;">⭐ Pro Verified</span> <?php endif; ?></p>
        </div>
    </div>

    <div class="card">
        <h3>My Specialties</h3>
        <?php while($row = $services_query->fetch_assoc()): ?>
            <span class="service-tag"><?php echo htmlspecialchars($row['services_name']); ?></span>
        <?php endwhile; ?>
        
        <?php if ($tech['plan_type'] == 'Premium'): ?>
            <br><br><a href="edit_services.php" style="font-size: 0.8em; color: #007bff; text-decoration: none;">+ Manage Categories</a>
        <?php endif; ?>
    </div>

    <?php if ($tech['plan_type'] == 'Premium'): ?>
        <div class="card premium">
            <h3>👑 Premium Pro Tools</h3>
            <a href="advanced_analytics.php" class="btn" style="background: #ffd700; color: #333;">View Advanced Analytics</a>
        </div>
    <?php else: ?>
        <div class="card" style="border: 1px solid #007bff;">
            <h3>🚀 Upgrade to Premium</h3>
            <p>Unlock unlimited monthly job allocations, category modifications, and detailed customer insights.</p>
            <a href="upgrade_premium.php" class="btn" style="background: #007bff;">Upgrade Now</a>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3>Active Assigned Jobs</h3>
        <table>
            <thead>
                <tr style="background: #f8f9fa;">
                    <th>Customer</th><th>Address</th><th>Phone Number</th><th>Date & Time</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $assigned_jobs = $conn->query("SELECT B.*, C.customer_name, C.customer_address, C.customer_phonenum
                                           FROM BOOKING B 
                                           JOIN CUSTOMERS C ON B.customer_id = C.customer_id 
                                           WHERE B.technician_id = $tech_id AND B.booking_status = 'Pending' 
                                           ORDER BY B.booking_date ASC");

            if ($assigned_jobs->num_rows > 0) {
                while ($job = $assigned_jobs->fetch_assoc()) {
                    // WhatsApp Link Logic
                    $clean_phone = preg_replace('/[^0-9]/', '', $job['customer_phonenum']);
                    $wa_msg = urlencode("Hello " . $job['customer_name'] . ", I am your technician from HouseFix. I am reaching out regarding your booking.");
                    $wa_link = "https://wa.me/" . $clean_phone . "?text=" . $wa_msg;
                    echo "<tr>
                    <td>".htmlspecialchars($job['customer_name'])."</td>
                    <td>".htmlspecialchars($job['customer_address'])."</td>
                    
                    <td>
                        <a href='{$wa_link}' target='_blank' style='background: #25d366; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 0.9em; display: inline-block;'>
                            💬 " . htmlspecialchars($job['customer_phonenum']) . "
                        </a>
                    </td>
        
            <td>{$job['booking_date']} {$job['booking_time']}</td>";

            // Job Status Action
            if ($limit_reached) {
                echo "<span>Locked</span>";
            } else {
                echo "<td><a href='complete_job.php?id={$job['booking_id']}' onclick='return confirm(\"Mark as completed?\")' style='color: green;'>Pending</a>";
            }
            echo "</td></tr>";
                }
            } else { echo "<tr><td colspan='4'>No active jobs.</td></tr>"; }
            ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>Customer Reviews & Feedback</h3>
        <?php if ($reviews_query->num_rows > 0): ?>
            <?php while ($review = $reviews_query->fetch_assoc()): ?>
                <div class="review-item">
                    <div style="display: flex; justify-content: space-between;">
                        <strong><?php echo htmlspecialchars($review['customer_name']); ?></strong>
                        <span class="stars">
                            <?php echo str_repeat("★", $review['rating_score']) . str_repeat("☆", 5 - $review['rating_score']); ?>
                        </span>
                    </div>
                    <small style="color: #999;"><?php echo date('M d, Y', strtotime($review['review_date'])); ?></small>
                    
                    <div style="margin-top: 8px;">
                        <?php if ($tech['plan_type'] == 'Premium'): ?>
                            <p style="margin: 5px 0 0 0; color: #444;"><?php echo htmlspecialchars($review['review_comment']); ?></p>
                        <?php else: ?>
                            <span class="locked-comment">🔒 Comment hidden. Upgrade to Premium to read customer text feedback.</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #777;">No reviews received yet.</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>