<?php
// Start the session to track booking attributes across pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('dbconn.php');

// 1. If the user is arriving from the booking form, temporarily save their inputs into the Session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cust_name'])) {
    $_SESSION['cust_name']    = $_POST['cust_name'];
    $_SESSION['cust_phone']   = $_POST['cust_phone'];
    $_SESSION['cust_email']   = $_POST['cust_email'];
    $_SESSION['cust_address'] = $_POST['cust_address'];
    $_SESSION['booking_date'] = $_POST['booking_date'];
    $_SESSION['booking_time'] = $_POST['booking_time'];
}

// Extract variables from session for easy access
$service_id    = isset($_SESSION['booking_service_id']) ? intval($_SESSION['booking_service_id']) : 0;
$technician_id = isset($_SESSION['booking_tech_id']) ? intval($_SESSION['booking_tech_id']) : 0;

// Fetch the service details from the database
$service_query = "SELECT services_name, services_fee FROM SERVICES WHERE services_id = $service_id";
$service_result = $conn->query($service_query);
$service_data = $service_result ? $service_result->fetch_assoc() : null;

// Fetch selected technician name
$tech_query = "SELECT tech_name FROM TECHNICIAN WHERE technician_id = $technician_id";
$tech_result = $conn->query($tech_query);
$tech_data = $tech_result ? $tech_result->fetch_assoc() : null;

$base_fee = $service_data ? $service_data['services_fee'] : 0.00;
$platform_fee = 5.00; 
$total_fee = $base_fee + $platform_fee;

// 2. PROCESS DATABASE INSERTS & TELEGRAM NOTIFICATION
if (isset($_POST['execute_payment'])) {
    
    $cust_name    = $conn->real_escape_string($_SESSION['cust_name']);
    $cust_phone   = $conn->real_escape_string($_SESSION['cust_phone']);
    $cust_email   = $conn->real_escape_string($_SESSION['cust_email']);
    $cust_address = $conn->real_escape_string($_SESSION['cust_address']);
    $b_date       = $_SESSION['booking_date'];
    $b_time       = $_SESSION['booking_time'];
    
    // Insert into CUSTOMERS
    $customer_sql = "INSERT INTO CUSTOMERS (customer_name, customer_address, customer_phonenum, customer_email) 
                     VALUES ('$cust_name', '$cust_address', '$cust_phone', '$cust_email')";
    $conn->query($customer_sql);
    $new_customer_id = $conn->insert_id; 
    
    // Insert into BOOKING
    $booking_sql = "INSERT INTO BOOKING (booking_date, booking_time, booking_status, customer_id, technician_id) 
                    VALUES ('$b_date', '$b_time', 'Pending', $new_customer_id, $technician_id)";
    $conn->query($booking_sql);
    $new_booking_id = $conn->insert_id; 
    
    // Insert into BOOKING_DETAIL
    $detail_sql = "INSERT INTO BOOKING_DETAIL (booking_id, services_id) 
                   VALUES ($new_booking_id, $service_id)";
    $conn->query($detail_sql);
    
    // -------------------------------------------------------------------------
    // 🤖 AUTOMATIC TELEGRAM NOTIFICATION
    // -------------------------------------------------------------------------
    $tracking_url = "http://localhost/CSC_574/HouseFix/tracking.php?booking_id=" . $new_booking_id;
    
    $tg_text = "<b>⚡ HouseFix Booking Confirmed (Ref: #{$new_booking_id})</b>\n\n";
    $tg_text .= "Hello " . htmlspecialchars($cust_name) . ",\n";
    $tg_text .= "Your repair request has been logged successfully!\n\n";
    $tg_text .= "🛠️ <b>Service:</b> " . ($service_data ? htmlspecialchars($service_data['services_name']) : 'General Fix') . "\n";
    $tg_text .= "👨‍🔧 <b>Assigned Tech:</b> " . ($tech_data ? htmlspecialchars($tech_data['tech_name']) : 'Expert') . "\n";
    $tg_text .= "🗓️ <b>Appointment:</b> {$b_date} @ {$b_time}\n";
    $tg_text .= "📍 <b>Address:</b> " . htmlspecialchars($cust_address) . "\n\n";
    $tg_text .= "🌐 <b>TRACK PROGRESS LIVE:</b>\n" . $tracking_url;

    $bot_token = '8838650204:AAFj6Pk8QMAcWIoKFJgG78Ly_1wgGjfAwIM';
    $chat_id   = '778931043';

    $telegram_api_url = "https://api.telegram.org/bot{$bot_token}/sendMessage?chat_id={$chat_id}&parse_mode=HTML&text=" . urlencode($tg_text);
    
    @file_get_contents($telegram_api_url);
    
    // Clear Session & Redirect
    unset($_SESSION['cust_name'], $_SESSION['cust_phone'], $_SESSION['cust_email'], $_SESSION['cust_address']);
    header("Location: tracking.php?booking_id=" . $new_booking_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix - Payment Summary</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        .header { background-color: #f8f9fa; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; }
        .container { padding: 40px; max-width: 500px; margin: 0 auto; }
        .invoice-box { border: 1px solid #ccc; padding: 30px; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .invoice-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px; }
        .total-row { border-top: 2px dashed #ddd; margin-top: 20px; padding-top: 15px; }
        .btn-pay { background-color: #007bff; color: white; padding: 14px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; width: 100%; margin-top: 25px; font-weight: bold; }
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
        <div class="invoice-box">
            <h2 style="text-align: center; margin-top: 0; color: #333;">Payment Summary</h2>
            <form action="payment.php" method="POST">
                <div class="invoice-row">
                    <span><strong>Technician:</strong></span>
                    <span><?php echo $tech_data ? htmlspecialchars($tech_data['tech_name']) : 'Unknown'; ?></span>
                </div>
                <div class="invoice-row">
                    <span><strong>Service:</strong></span>
                    <span><?php echo $service_data ? htmlspecialchars($service_data['services_name']) : 'Unknown'; ?></span>
                </div>
                <div class="invoice-row">
                    <span><strong>Schedule:</strong></span>
                    <span><?php echo htmlspecialchars($_SESSION['booking_date'] ?? '') . " (" . htmlspecialchars($_SESSION['booking_time'] ?? '') . ")"; ?></span>
                </div>
                <div class="invoice-row total-row">
                    <span>Total Due:</span>
                    <span>RM<?php echo number_format($total_fee, 2); ?></span>
                </div>
                <button type="submit" name="execute_payment" class="btn-pay">Pay & Confirm Booking</button>
            </form>
        </div>
    </div>
</body>
</html>