<?php
session_start();
include('dbconn.php');

// Check if technician is logged in
if (!isset($_SESSION['technician_id'])) {
    header("Location: tech_login.php");
    exit();
}

$tech_id = $_SESSION['technician_id'];

// Fetch current technician data
$query = $conn->query("SELECT * FROM TECHNICIAN WHERE technician_id = $tech_id");
$tech = $query->fetch_assoc();

$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['tech_name']);
    $phone = $conn->real_escape_string($_POST['tech_phonenum']);
    // Email is NOT updated - keep as is
    
    // Handle photo upload
    $photo_path = $tech['photo_path'];
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES['photo']['name']);
        $target_file = $upload_dir . $file_name;
        
        // Check if file is image
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                // Delete old photo if exists and not default
                if (!empty($tech['photo_path']) && $tech['photo_path'] != 'uploads/technician.png' && file_exists($tech['photo_path'])) {
                    unlink($tech['photo_path']);
                }
                $photo_path = $target_file;
            }
        }
    }
    
    // Update database (without email)
    $update = $conn->query("UPDATE TECHNICIAN SET 
                            tech_name = '$name', 
                            tech_phonenum = '$phone',
                            photo_path = '$photo_path'
                            WHERE technician_id = $tech_id");
    
    if ($update) {
        $success = "Profile updated successfully!";
        // Refresh data
        $query = $conn->query("SELECT * FROM TECHNICIAN WHERE technician_id = $tech_id");
        $tech = $query->fetch_assoc();
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HouseFix | Edit Profile</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding: 0; 
            padding-top: 80px;
        }
        
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: center;
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
        
        .logo { 
            font-size: 24px; 
            font-weight: bold; 
            color: #f8eed3; 
            text-align: center;
        }
        
        .container { 
            max-width: 500px; 
            margin: 30px auto; 
            background: #fbf7e9; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        
        h2 { margin-top: 0; color: #734d26; }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #734d26;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #fff;
        }
        
        /* Style for readonly/disabled input */
        input[readonly] {
            background-color: #e8dcc8;
            cursor: not-allowed;
        }
        
        .current-photo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #734d26;
        }
        
        button {
            background: #734d26;
            color: #f8eed3;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        
        button:hover {
            background: #5a3b1e;
        }
        
        .btn-back {
            background: #734d26;
            margin-top: 10px;
        }
        
        .btn-back:hover {
            background: #5a3b1e;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info-text {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
</div>

<div class="container">
    <br><h2>Edit Profile</h2>
    
    <?php if ($success): ?>
        <div class="success">✅ <?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error">❌ <?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="current-photo">
            <?php 
            $photo = !empty($tech['photo_path']) && file_exists($tech['photo_path']) 
                ? $tech['photo_path'] 
                : 'uploads/technician.png';
            ?>
            <img src="<?php echo $photo; ?>" alt="Current Photo">
            <p style="font-size: 12px; color: #666;">Current Photo</p>
        </div>
        
        <div class="form-group">
            <label>Upload New Photo (Optional)</label>
            <input type="file" name="photo" accept="image/*">
        </div>
        
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="tech_name" value="<?php echo htmlspecialchars($tech['tech_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="tech_phonenum" value="<?php echo htmlspecialchars($tech['tech_phonenum']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="tech_email" value="<?php echo htmlspecialchars($tech['tech_email']); ?>" readonly>
            <div class="info-text">Email cannot be changed. Contact admin if you need to update your email.</div>
        </div>
        
        <button type="submit">Save Changes</button>
    </form>
    
    <form action="tech_dashboard.php" method="get">
        <button type="submit" class="btn-back">Back to Dashboard</button>
    </form>
</div>
<footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>