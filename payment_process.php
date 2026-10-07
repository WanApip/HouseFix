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
        <body style="text-align: center; padding: 50px; font-family: sans-serif;">
            <h1 style="color: green;">🎉 Payment Successful!</h1>
            <p>Your account has been upgraded to the Premium Plan.</p>
            <p>You now have access to advanced analytics and unlimited jobs.</p>
            <br>
            <a href="tech_dashboard.php" style="padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 5px;">
                Go to Dashboard
            </a>
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