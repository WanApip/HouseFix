<?php
session_start();
include('dbconn.php');

if (!isset($_SESSION['technician_id'])) {
    header("Location: tech_login.php");
    exit();
}

$tech_id = $_SESSION['technician_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upgrade Your Plan | HouseFix</title>
    <style>
        body { font-family: sans-serif; background-color: #f8eed3; padding: 20px; }
        .header { background-color: #734d26; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        .header-logo {
            height: 60px; /* Adjust this value to make it the right size */
            width: auto;   /* Maintains aspect ratio */
            display: block;
        }
        .pricing-container { display: flex; gap: 20px; justify-content: center; padding: 120px 50px 50px 50px; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 10px; width: 300px; text-align: center; background: #fbf7e9; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .premium { border: 2px solid #ffd700; }
        .btn { padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; cursor: pointer; border: none; font-weight: bold; }
        .btn-premium { background: #734d26; color: #f8eed3; width: 100%; margin-top: 20px; }
        
        /* Modal Styles */
        #modal-trigger { display: none; }
        .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:2000; }
        .modal-content { background:white; margin:10% auto; padding:25px; width:320px; border-radius:8px; text-align:center; }
        .payment-fields { display: none; margin: 20px 0; }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
        #qr-radio:checked ~ #qr-section { display: block; }
        #cc-radio:checked ~ #cc-section { display: block; }
        #modal-trigger:checked + .modal { display: block; }
    </style>
</head>
<body>
    <div class="header">
        <form action="tech_dashboard.php"><button class="btn" style="background:#f8eed3; color:#734d26;">Back</button></form>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div></div>
    </div>

    <div class="pricing-container">
        <div class="card">
            <h2>Basic</h2>
            <p><strong>Free</strong></p>
            <ul style="text-align: left;"><li>10 monthly jobs</li><li>Single category</li></ul>
            <br><br><br>
            <a href="tech_dashboard.php" class="btn" style="background:#734d26; color:#f8eed3;">Stay on Basic</a>
        </div>

        <div class="card premium">
            <h2>Premium</h2>
            <p><strong>RM 99.00</strong><br><small>(Lifetime Access)</small></p>
            <ul style="text-align: left;">
                <li>Unlimited monthly jobs</li>
                <li>Multi-category selector</li>
                <li>Advanced analytics</li>
                <li>Pro verified badge</li>
            </ul>
            <input type="checkbox" id="modal-trigger">
            <label for="modal-trigger" onclick="document.getElementById('paymentModal').style.display='block'" class="btn" style="background:#734d26; color:#f8eed3;">Subscribe to Premium</label>
        </div>
    </div>

    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <h3>Choose Payment Method</h3>
            <form action="payment_process.php" method="POST">
                <input type="radio" name="method" id="qr-radio" value="qr" required> QR Pay
                <input type="radio" name="method" id="cc-radio" value="cc"> Credit Card
                
                <div id="qr-section" class="payment-fields">
                    <img src="qr_image.jpg" alt="QR Code" style="width:150px;">
                    <p>Scan to pay RM 99.00</p>
                </div>
                
                <div id="cc-section" class="payment-fields">
                    <input type="text" name="bank_name" placeholder="Bank Name" style="width:90%; margin:5px;">
                    <input type="text" name="card_num" placeholder="Card Number" style="width:90%; margin:5px;">
                    <input type="text" name="cvv" placeholder="CVV" style="width:90%; margin:5px;">
                </div>
                
                <button type="submit" class="btn btn-premium">Proceed Payment</button>
                <label for="modal-trigger"  onclick="document.getElementById('paymentModal').style.display='none'" style="display:block; margin-top:10px; cursor:pointer;">Cancel</label>
            </form>
        </div>
    </div><br><br><br><br><br><br><br><br><br>
    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>