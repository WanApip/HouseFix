<?php
// Include database connection and session handling
include('db.php');

// Fetch all available services from the database
$services_query = "SELECT * FROM SERVICES";
$services_result = $conn->query($services_query);

// Fetch all areas from the database for the location picker
$area_query = "SELECT * FROM AREA";
$area_result = $conn->query($area_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix - Service Page</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header { background-color: #f8f9fa; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; }
        .container { padding: 40px; max-width: 800px; margin: 0 auto; }
        .section-title { font-size: 28px; margin-bottom: 20px; text-align: center; }
        .area-section { background-color: #f1f3f5; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #dee2e6; }
        .grid-services { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .service-card { border: 1px solid #ccc; padding: 20px; border-radius: 8px; background: #fff; text-align: center; }
        .service-card input { transform: scale(1.3); margin-top: 10px; cursor: pointer; }
        .submit-container { text-align: center; }
        .btn-submit { background-color: #007bff; color: white; padding: 14px 30px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; }
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

    <div class="container">
        <h2 class="section-title">Choose Your Service</h2>

        <form action="technicians.php" method="POST">
            
            <div class="area-section">
                <h3><label for="area_id">Select Your Service Area:</label></h3>
                <select name="area_id" id="area_id" style="width: 100%; padding: 10px; font-size: 16px;" required>
                    <option value="">-- Choose Your Town / Postcode --</option>
                    <?php while($area = $area_result->fetch_assoc()): ?>
                        <option value="<?php echo $area['area_id']; ?>">
                            <?php echo $area['postcode'] . " - " . $area['town'] . " (" . $area['state'] . ")"; ?>
                        </option>
                    <?php endphp; ?>
                </select>
            </div>

            <div class="grid-services">
                <?php while($service = $services_result->fetch_assoc()): ?>
                    <div class="service-card">
                        <h3><?php echo htmlspecialchars($service['services_name']); ?></h3>
                        <p style="font-size: 14px; color: #666; min-height: 40px;"><?php echo htmlspecialchars($service['services_description']); ?></p>
                        <p><strong>Base Rate: RM<?php echo number_format($service['services_fee'], 2); ?></strong></p>
                        
                        <input type="checkbox" name="selected_services[]" value="<?php echo $service['services_id']; ?>">
                    </div>
                <?php endphp; ?>
            </div>

            <div class="submit-container">
                <button type="submit" class="btn-submit">Find Technician</button>
            </div>

        </form>
    </div>

</body>
</html>