<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('dbconn.php');

// take the chosen technician ID from the previous page's form submit
$technician_id = isset($_POST['technician_id']) ? intval($_POST['technician_id']) : 0;

if ($technician_id === 0) {
    header("Location: services.php");
    exit();
}

// Save the technician ID securely into the session basket
$_SESSION['booking_tech_id'] = $technician_id;

// Fetch the technician's name from database 
$tech_query = "SELECT tech_name FROM TECHNICIAN WHERE technician_id = $technician_id";
$tech_result = $conn->query($tech_query);
$tech_data = $tech_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix - Booking Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        .header { background-color: #f8f9fa; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; }
        .container { padding: 40px; max-width: 600px; margin: 0 auto; }
        .form-section { border: 1px solid #ccc; padding: 30px; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #444; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; }
        .btn-next { background-color: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 10px; }
        .summary-banner { background-color: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 25px; border-left: 5px solid #007bff; font-size: 16px; }
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
        <h2>Booking Details</h2>
        
        <div class="summary-banner">
            🎯 Technician Name: <strong><?php echo htmlspecialchars($tech_data['tech_name']); ?></strong>
        </div>

        <form action="payment.php" method="POST" class="form-section">
            
            <h3 style="margin-top: 0; color: #007bff;">1. Schedule Appointment</h3>
            <div class="form-group">
                <label for="booking_date">Select Date:</label>
                <input type="date" id="booking_date" name="booking_date" required>
            </div>

            <div class="form-group">
                <label for="booking_time">Select Time:</label>
                <input type="time" id="booking_time" name="booking_time" required>
            </div>

            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

            <h3 style="color: #007bff;">2. Your Information</h3>
            
            <div class="form-group">
                <label for="cust_name">Full Name:</label>
                <input type="text" id="cust_name" name="cust_name" placeholder="e.g. Muhammad..." required>
            </div>

            <div class="form-group">
                <label for="cust_phone">Phone Number:</label>
                <input type="tel" id="cust_phone" name="cust_phone" placeholder="e.g. 012-3456789" required>
            </div>

            <div class="form-group">
                <label for="cust_email">Email Address:</label>
                <input type="email" id="cust_email" name="cust_email" placeholder="e.g. muhammad@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="cust_address">Service Address (Where should the tech go?):</label>
                <textarea id="cust_address" name="cust_address" rows="3" placeholder="e.g. No. 45, Jalan Merdeka..." required></textarea>
            </div>

            <button type="submit" class="btn-next">Proceed to Summary</button>

        </form>
    </div>

</body>
</html>