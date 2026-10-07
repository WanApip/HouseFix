<?php
session_start();
include('dbconn.php');

// Define variables to handle messages
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // 1. Query to find the user by email
    $sql = "SELECT * FROM TECHNICIAN WHERE tech_email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // 2. Check Verification Status
        if ($row['tech_status'] == 'Approved') {
            // Verify the hashed password
             if ($password == $row['password_hash']) {
                $_SESSION['technician_id'] = $row['technician_id'];
                $_SESSION['tech_name'] = $row['tech_name'];
                
                // Redirect to the Dashboard
                header("Location: tech_dashboard.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } elseif ($row['tech_status'] == 'Pending') {
            // Case: Registered but awaiting Admin
            $message = "Your account is registered, but it is still under Admin review. Please wait for approval.";
        } else {
            // Case: Rejected or other status
            $message = "Your account access has been restricted. Contact support.";
        }
    } else {
        $message = "No account found with this email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Technician Login | HouseFix</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: #d9534f; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Technician Login</h2>
    <?php if ($message) echo "<p class='error'>$message</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
    </form>
    <p style="margin-top: 15px; font-size: 13px;">Don't have an account? <a href="tech_register.php">Register here</a></p>
</div>

</body>
</html>