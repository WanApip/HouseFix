<?php
session_start();
include('dbconn.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escape inputs to prevent SQL Injection
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // 1. Query the ADMIN table based on your database structure
    $sql = "SELECT * FROM ADMIN WHERE username_admin = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // 2. Verify password (Direct comparison as per your table)
        if ($password == $row['password_hash']) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_user'] = $row['username_admin'];
            
            // Redirect to Admin Dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f8eed3; }
        .header { background-color: #734d26; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        .header-logo {
            height: 60px; /* Adjust this value to make it the right size */
            width: auto;   /* Maintains aspect ratio */
            display: block;
        }
        .center-container { display: flex; justify-content: center; align-items: center; min-height: 80vh; }
        .login-card { background: #fbf7e9; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #734d26; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background-color: #734d26; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .error { color: #d9534f; font-size: 14px; text-align: center; background: #f8d7da; padding: 8px; border-radius: 4px; }
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
            <h2>Admin Login</h2>
            <?php if ($message) echo "<p class='error'>$message</p>"; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>