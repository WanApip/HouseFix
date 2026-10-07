<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('dbconn.php');

// Grab the unique booking reference ID from the URL string
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id === 0) {
    echo "<div style='padding:50px; text-align:center; font-family:Arial, sans-serif;'><h3>Invalid Booking Reference Number.</h3><a href='services.php'>Go Back to Services</a></div>";
    exit();
}

// Fetch the real-time booking status along with the assigned technician's name
$query = "SELECT b.booking_status, b.booking_date, b.booking_time, t.tech_name 
          FROM BOOKING b
          JOIN TECHNICIAN t ON b.technician_id = t.technician_id
          WHERE b.booking_id = $booking_id";
          
$result = $conn->query($query);
$booking = $result ? $result->fetch_assoc() : null;

if (!$booking) {
    echo "<div style='padding:50px; text-align:center; font-family:Arial, sans-serif;'><h3>Booking record not found.</h3><a href='services.php'>Go Back to Services</a></div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix - Track Your Fixer</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        .header { background-color: #f8f9fa; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; }
        .container { padding: 40px; max-width: 600px; margin: 0 auto; text-align: center; }
        .status-box { border: 1px solid #ccc; padding: 30px; border-radius: 8px; background-color: #fff; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .status-banner { display: inline-block; padding: 10px 20px; font-weight: bold; border-radius: 20px; font-size: 18px; text-transform: uppercase; margin: 15px 0; }
        
        /* Dynamic state alert colors */
        .status-pending { background-color: #ffeeba; color: #856404; }
        .status-progress { background-color: #b8daff; color: #004085; }
        .status-completed { background-color: #c3e6cb; color: #155724; }
        
        .btn-rate { display: inline-block; background-color: #ffc107; color: #212529; padding: 12px 24px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; text-decoration: none; font-weight: bold; margin-top: 20px; width: 80%; text-align: center; }
        .btn-locked { background-color: #e9ecef; color: #adb5bd; padding: 12px 24px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; cursor: not-allowed; width: 85%; margin-top: 20px; font-weight: bold; }
        .simulation-tip { margin-top: 40px; padding: 15px; background-color: #fff; border: 1px dashed #6c757d; border-radius: 6px; font-size: 14px; text-align: left; line-height: 1.5; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">HouseFix</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="login.php">Login</a>
        </div>
    </div>

    <div class="container">
        <h2>Booking Reference: #<?php echo $booking_id; ?></h2>
        
        <div class="status-box">
            <h3>Assigned Fixer: <?php echo htmlspecialchars($booking['tech_name']); ?></h3>
            <p>Scheduled Time: <strong><?php echo htmlspecialchars($booking['booking_date']) . " @ " . htmlspecialchars($booking['booking_time']); ?></strong></p>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            
            <p style="color: #555; font-size: 15px;">Live Job Status:</p>
            
            <?php 
            $status = $booking['booking_status'];
            
            // Render different structural styles based on database status value
            if ($status === 'Pending') {
                echo '<span class="status-banner status-pending">Pending Acceptance</span>';
                echo '<p style="color:#777; font-size: 14px;">Waiting for the technician to accept your repair job request.</p>';
            } elseif ($status === 'In Progress') {
                echo '<span class="status-banner status-progress">In Progress</span>';
                echo '<p style="color:#777; font-size: 14px;">The technician is currently on-site working on your issue.</p>';
            } elseif ($status === 'Completed') {
                echo '<span class="status-banner status-completed">Completed</span>';
                echo '<p style="color:#28a745; font-weight:bold; font-size: 14px;">Your maintenance issue has been completely fixed!</p>';
            } else {
                echo '<span class="status-banner status-pending">' . htmlspecialchars($status) . '</span>';
            }
            ?>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <?php 
            // Logical structural gate: Clickable rating link unlocks ONLY if status is 'Completed'
            if ($status === 'Completed') { 
            ?>
                <a href="review.php?booking_id=<?php echo $booking_id; ?>" class="btn-rate">⭐ Leave a Review / Rating</a>
            <?php 
            } else { 
            ?>
                <button class="btn-locked" disabled>🔒 Review Blocked (Available Once Job is Completed)</button>
            <?php 
            } 
            ?>
        </div>

        <div class="simulation-tip">
            <strong>💡 Local Demonstration Guide:</strong><br>
            Right now, your fresh insert creates a row set to <code>Pending</code>. To simulate your technician completing the actual job for a live demo:
            <ol style="margin: 5px 0 0 20px; padding: 0;">
                <li>Open phpMyAdmin and select your <code>BOOKING</code> table.</li>
                <li>Locate the row tracking booking ID <strong>#<?php echo $booking_id; ?></strong>.</li>
                <li>Double-click the value under <code>booking_status</code> and change it manually to <strong>Completed</strong>.</li>
                <li>Refresh this tracking webpage to see the rating system unlock automatically!</li>
            </ol>
        </div>
    </div>

</body>
</html>