<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('dbconn.php');

// 1. Capture and Save Data if coming from the form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cust_name'])) {
    $_SESSION['cust_name']    = $_POST['cust_name'];
    $_SESSION['cust_phone']   = $_POST['cust_phone'];
    $_SESSION['cust_email']   = $_POST['cust_email'];
    $_SESSION['cust_address'] = $_POST['cust_address'];
    $_SESSION['booking_date'] = $_POST['booking_date'];
    $_SESSION['booking_time'] = $_POST['booking_time'];
}

// 2. --- MOVE VALIDATION HERE (Before displaying the page) ---
$technician_id = isset($_SESSION['booking_tech_id']) ? intval($_SESSION['booking_tech_id']) : 0;
$b_date        = $_SESSION['booking_date'];
$b_time        = $_SESSION['booking_time'];

$check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM BOOKING WHERE technician_id = ? AND booking_date = ? AND booking_time = ? AND booking_status != 'Cancelled'");
$check_stmt->bind_param("iss", $technician_id, $b_date, $b_time);
$check_stmt->execute();
$result = $check_stmt->get_result()->fetch_assoc();

if ($result['count'] > 0) {
    echo "<script>
            alert('Error: This technician is already booked at that time. Please choose another slot.');
            window.location.href = 'booking.php?technician_id=" . $technician_id . "';
          </script>";
    exit();
}
    // -----------------------------------------------
    
    $customer_sql = "INSERT INTO CUSTOMERS (customer_name, customer_address, customer_phonenum, customer_email) VALUES ('$cust_name', '$cust_address', '$cust_phone', '$cust_email')";
    $conn->query($customer_sql);
    $new_customer_id = $conn->insert_id; 
    
    $booking_sql = "INSERT INTO BOOKING (booking_date, booking_time, booking_status, customer_id, technician_id) VALUES ('$b_date', '$b_time', 'Pending', $new_customer_id, $technician_id)";
    $conn->query($booking_sql);
    $new_booking_id = $conn->insert_id; 
    
    $detail_sql = "INSERT INTO BOOKING_DETAIL (booking_id, services_id) VALUES ($new_booking_id, $service_id)";
    $conn->query($detail_sql);
    
    $tracking_url = "http://localhost/CSC_574/HouseFix/tracking.php?booking_id=" . $new_booking_id;
    $tg_text = "<b>⚡ HouseFix Booking Confirmed (Ref: #{$new_booking_id})</b>\n\nHello " . htmlspecialchars($cust_name) . ",\nYour repair request has been logged successfully!\n\n🛠️ <b>Service:</b> " . ($service_data ? htmlspecialchars($service_data['services_name']) : 'General Fix') . "\n👨‍🔧 <b>Assigned Tech:</b> " . ($tech_data ? htmlspecialchars($tech_data['tech_name']) : 'Expert') . "\n🗓️ <b>Appointment:</b> {$b_date} @ {$b_time}\n📍 <b>Address:</b> " . htmlspecialchars($cust_address) . "\n\n🌐 <b>TRACK PROGRESS LIVE:</b>\n" . $tracking_url;

    $bot_token = '8838650204:AAFj6Pk8QMAcWIoKFJgG78Ly_1wgGjfAwIM';
    $chat_id   = '778931043';
    $telegram_api_url = "https://api.telegram.org/bot{$bot_token}/sendMessage?chat_id={$chat_id}&parse_mode=HTML&text=" . urlencode($tg_text);
    @file_get_contents($telegram_api_url);
    
    unset($_SESSION['cust_name'], $_SESSION['cust_phone'], $_SESSION['cust_email'], $_SESSION['cust_address']);
    header("Location: tracking.php?booking_id=" . $new_booking_id);
    exit();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Base styles */
        body { font-family: Arial, sans-serif; background-color: #f8eed3; }
        .container { padding: 100px 40px; max-width: 500px; margin: 0 auto; }
        .invoice-box { border: 1px solid #ccc; padding: 30px; border-radius: 8px; background-color: #fbf7e9; }
        .btn-pay { background-color: #734d26; color: white; padding: 14px; border: none; border-radius: 4px; width: 100%; font-weight: bold; cursor: pointer; }
        
        /* Modal - Using display:none by default */
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:2000; }
        .modal-content { background:white; margin:10% auto; padding:25px; width:320px; border-radius:8px; text-align:center; }
        
        /* CSS Toggle Logic */
        .payment-fields { display: none; margin: 20px 0; }
        #qr-radio:checked ~ #qr-section { display: block; }
        #cc-radio:checked ~ #cc-section { display: block; }
        
        /* Triggering the modal via checkbox hack */
        #modal-trigger { display: none; }
        #modal-trigger:checked ~ .modal { display: block; }
    </style>
    <title>HouseFix | Payment </title>
</head>
<body>

     <div class="container">

        <div class="invoice-box">

            <h2 style="text-align: center; color: #734d26;">Payment Summary</h2>
            <p>Technician: <?php echo $tech_data ? htmlspecialchars($tech_data['tech_name']) : 'Unknown'; ?></p>
            <p>Service: <?php echo $service_data ? htmlspecialchars($service_data['services_name']) : 'Unknown'; ?></p>
            <p>Service Fee: </strong> RM<?php echo number_format($base_fee, 2); ?></p>
            <p>Platform Fee: </strong> RM<?php echo number_format($platform_fee, 2); ?></p>
            <p><strong>Total Due: </strong> RM<?php echo number_format($total_fee, 2); ?></p>

            <button type="button" class="btn-pay" onclick="document.getElementById('paymentModal').style.display='block'">Pay & Confirm Booking</button>

        </div>

    </div>
            <div id="paymentModal" class="modal">
                <div class="modal-content">
                    <h3>Choose Payment Method</h3>
                    <form action="" method="POST">
                        <input type="radio" name="method" id="qr-radio" value="qr" required> QR Pay
                        <input type="radio" name="method" id="cc-radio" value="cc"> Credit Card<br>
                        
                        <div id="qr-section" class="payment-fields">
                            <img src="qr_image.jpg" alt="QR Code" style="width:150px;">
                            <p>Scan the code above to pay.</p>
                        </div>
                        
                        <div id="cc-section" class="payment-fields">
                            <input type="text" name="bank_name" placeholder="Bank Name" style="width:90%; margin:5px;">
                            <input type="text" name="card_num" placeholder="Card Number" style="width:90%; margin:5px;">
                            <input type="text" name="cvv" placeholder="CVV" style="width:90%; margin:5px;">
                        </div>
                        <br>

                        <button type="submit" name="execute_payment" class="btn-pay">Proceed Payment</button>
                        
                        <button type="button" onclick="document.getElementById('paymentModal').style.display='none'" style="margin-top:10px; padding:10px; width:100%; cursor:pointer;">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>