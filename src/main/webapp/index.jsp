<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Home Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- This single line links your external styles! -->
    <link rel="stylesheet" href="style.css">
</head>
<body bgcolor="#f8eed3">

    <div class="header">
        <div class="logo">Welcome to HouseFix !</div>
        <div class="nav-links">
            <a href="HouseFixController?action=showServices">Services</a>
            <a> | </a>
            <a href="HouseFixController?action=showTechLogin">Login</a>
        </div>
    </div>

    <div class="main">
        <img src="logobesar2.png" alt="HouseFix Logo" width="285">
        
        <div class="main-container">
            <div class="action-card">
                <h3>Book for Services</h3>
                <p>Find trusted technicians for your home needs</p>
                <a href="HouseFixController?action=showServices"><button class="btn">Start Booking</button></a>
            </div>

            <div class="action-card">
                <h3>Become a Technician</h3>
                <p>Join HouseFix and get more job opportunities</p>
                <a href="HouseFixController?action=showTechRegister"><button class="btn">Register Here</button></a>
                <div class="login-link">
                    <p style="font-size: 13px; color: #777; margin-top: 15px;">Already a technician? <a href="HouseFixController?action=showTechLogin" style="color: #734d26; font-weight: 600; text-decoration: none;">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com<br><br>A Website Owner? <a href="HouseFixController?action=showAdminLogin">Login Now</a>
    </footer>

</body>
</html>