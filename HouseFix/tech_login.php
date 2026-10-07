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
    <title>HouseFix | Technician Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8eed3; }
        
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #ddd; 
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .header-logo {
            height: 60px; /* Adjust this value to make it the right size */
            width: auto;   /* Maintains aspect ratio */
            display: block;
        }
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        
        .nav-links a { 
            margin-left: 25px; 
            text-decoration: none; 
            color: #f8eed3;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            color: #ffd700;
        }
        
        /* Center container for login card */
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 90px);
            padding: 20px;
            margin-top: 70px;
        }
        
        .login-card { 
            background: #fbf7e9; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 350px; 
        }
        
        .login-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #734d26;
        }
        
        input { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
            background-color: #fff;
        }
        
        .btn { 
            width: 100%; 
            padding: 12px; 
            background-color: #734d26; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #5a3b1e;
        }
        
        .btnexit { 
            width: 100%; 
            padding: 12px; 
            background-color: #6c757d; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px;
        }
        
        .btnexit:hover {
            background-color: #5a6268;
        }
        
        .error {color: #d9534f; font-size: 14px; margin-bottom: 10px; text-align: center; background: #f8d7da; padding: 8px; border-radius: 4px;}
        .register-link {text-align: center; margin-top: 15px; font-size: 13px;}
        .register-link p { color: #333; }
        .register-link a {color: #734d26; text-decoration: none; font-weight: bold;}
        .register-link a:hover {text-decoration: underline;}
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>
<div class="header">
        <form action="homePage.php" style="margin: 0;">
             <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
        </form>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div class="nav-links">
            <a href="services.php"></a>
        </div>
    </div>

<div class="center-container">
    <div class="login-card">
        <h2>Technician Login</h2>
        <?php if ($message) echo "<p class='error'>$message</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="tech_register.php">Register here</a></p>
        </div>
    </div>
</div>
<footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>