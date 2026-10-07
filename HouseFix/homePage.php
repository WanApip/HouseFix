<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Home Page</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .header { background-color: #734d26; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; }
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #f8eed3; }
        .main { text-align: center; padding: 30px 20px; flex: 1; }
        .main-container { display: flex; justify-content: center; gap: 40px; padding: 20px; }
        .action-card { border: 1px solid #ccc; padding: 30px; width: 300px; text-align: center; border-radius: 8px; background-color: #fbf7e9; box-shadow: 0 4px 15px rgba(0,0,0,0.1);}
        .btn { width: 100%; padding: 12px; background-color: #734d26; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 20px;}
        .btn:hover { background-color: #5a3b1e;}
        
        /* Footer Styles */
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body bgcolor="#f8eed3">

    <div class="header">
        <div class="logo">Welcome to HouseFix</div>
        <div class="nav-links">
            <a href="services.php">Services</a>
            <a href="tech_login.php">Login</a>
        </div>
    </div>

    <div class="main">
        <img src="logobesar2.png" alt="HouseFix Logo" width="285">
        
        <div class="main-container">
            <div class="action-card">
                <h3>Book for Services</h3>
                <p>Find trusted technicians for your home needs</p>
                <a href="services.php"><button class="btn">Start Booking</button></a>
            </div>

            <div class="action-card">
                <h3>Become a Technician</h3>
                <p>Join HouseFix and get more job opportunities</p>
                <a href="tech_register.php"><button class="btn">Register Here</button></a>
                <div class="login-link">
                    <p style="font-size: 12px; color: #777; margin-top: 15px;">Already a technician? <a href="tech_login.php" style="color: #734d26; font-weight: bold;">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com<br><br>A Website Owner? <a href="admin_login.php">Login Now</a>
    </footer>

</body>
</html>