<?php
session_start();
include('dbconn.php');

// 1. Security Check: Ensure user is logged in
if (!isset($_SESSION['technician_id'])) {
    header("Location: tech_login.php");
    exit();
}

$tech_id = $_SESSION['technician_id'];

// 2. Process Payment (Simulation)
// In a real application, you would verify a 'success' token from the payment gateway here.
$payment_successful = true; 

if ($payment_successful) {
    // 3. Update the database to Premium
    $stmt = $conn->prepare("UPDATE TECHNICIAN SET plan_type = 'Premium', payment_status = 'Paid' WHERE technician_id = ?");
    $stmt->bind_param("i", $tech_id);
    
    if ($stmt->execute()) {
        // 4. Success UI
        ?>
        <!DOCTYPE html>
        <html>
        <head><title>Payment Successful</title></head>
        <style>
       
        .header { background-color: #734d26; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }

        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; }
        </style>
        <body style="margin: 0; font-family: sans-serif;">

  <div class="header">
    <form action="tech_login.php" style="margin: 0;">
         
    </form>
    <div class="logo">HouseFix</div>
    <div class="nav-links">
        <a href="tech_login.php" style="color: #f8eed3;"></a>
    </div>
</div>
  <div style="text-align: center; padding: 50px;">
    <h1 style="color: green;">🎉 Payment Successful!</h1>
    <p>Your account has been upgraded to the Premium Plan.</p>
    <p>You now have access to advanced analytics and unlimited jobs.</p>
    <br>
    <a href="tech_dashboard.php" style="padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 5px;">
      Go to Dashboard
    </a>
  </div>

</body>
        </html>
        <?php
    } else {
        echo "Error upgrading account: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Payment failed. Please try again.";
}
?>