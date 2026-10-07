<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix - Home Page</title>
    <style>
        
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header { background-color: #f8f9fa; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; }
        .main { text-align: center; padding: 50px 20px; }
        .main-container { display: flex; justify-content: center; gap: 40px; padding: 20px; }
        .action-card { border: 1px solid #ccc; padding: 30px; width: 300px; text-align: center; border-radius: 8px; background-color: #fff; }
        .btn { background-color: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .btn-secondary { background-color: #28a745; }
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

    <div class="main">
        <h1>Welcome to HouseFix</h1>
        <p>Making home repairs easy and reliable.</p>
    </div>

    <div class="main-container">
        
        <div class="action-card">
            <h3>Book for Services</h3>
            <p>Find trusted technicians for your home needs</p>
            <a href="services.php"><button class="btn">Start Booking</button></a>
        </div>

        <div class="action-card">
            <h3>Become a Technician</h3>
            <p>Join HouseFix and get more job opportunities</p>
            <a href="tech_register.php"><button class="btn btn-secondary">Register Here</button></a>
            <p style="font-size: 12px; color: #777; margin-top: 15px;">Already a technician?</p>
        </div>

    </div>

</body>
</html>