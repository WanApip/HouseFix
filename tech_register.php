<?php include('dbconn.php'); ?>
<!DOCTYPE html>
<html>
<head><title>Technician Registration | HouseFix</title></head>
<body>
    <h2>Join HouseFix as a Technician</h2>
    <form action="tech_register_process.php" method="POST">
        <input type="text" name="tech_name" placeholder="Full Name" required><br>
        <input type="email" name="tech_email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="text" name="tech_phonenum" placeholder="Phone" required><br>
        <input type="text" name="ssm_num" placeholder="SSM Number" required><br>
        <label>Select Your Working Area:</label>
        <select name="area_id" required>
            <option value="">-- Select Town --</option>
            <?php
            $areas = $conn->query("SELECT area_id, town FROM AREA ORDER BY town ASC");
            while($a = $areas->fetch_assoc()) {
                echo "<option value='{$a['area_id']}'>{$a['town']}</option>";
            }
            ?>
        </select><br><br>
        <label>Expert Job:</label>
        <select name="services_id" required>
            <?php
            $services = $conn->query("SELECT * FROM SERVICES");
            while($s = $services->fetch_assoc()) {
                echo "<option value='{$s['services_id']}'>{$s['services_name']}</option>";
            }
            ?>
        </select><br><br>
        
        <button type="submit">Submit Application</button>
    </form>
</body>
</html>