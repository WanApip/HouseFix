<?php
session_start();
include('dbconn.php');

// Check if admin is logged in (optional - add your own admin login check)
 if (!isset($_SESSION['admin_id'])) {
   header("Location: admin_login.php");
    exit();
 }

// Logic to update status
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $new_status = ($_GET['action'] == 'approve') ? 'Approved' : (($_GET['action'] == 'reject') ? 'Rejected' : 'Suspended');
    $conn->query("UPDATE TECHNICIAN SET tech_status = '$new_status' WHERE technician_id = $id");
    header("Location: manage_techs.php");
    exit();
}

// Fetch techs with their services joined using GROUP_CONCAT
$query = "SELECT T.*, GROUP_CONCAT(S.services_name SEPARATOR ', ') as services_list 
          FROM TECHNICIAN T 
          LEFT JOIN TECHNICIAN_SERVICES TS ON T.technician_id = TS.technician_id 
          LEFT JOIN SERVICES S ON TS.services_id = S.services_id 
          GROUP BY T.technician_id";

$all_techs = $conn->query($query);

$pending = [];
$active = [];
while ($row = $all_techs->fetch_assoc()) {
    if ($row['tech_status'] == 'Pending') {
        $pending[] = $row;
    } else {
        $active[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HouseFix Admin | Manage Technicians</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding: 0; 
            padding-top: 80px;
        }
        
        /* Fixed Header */
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

        .logo { 
            font-size: 24px; 
            font-weight: bold; 
            color: #f8eed3; 
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .btn-back {
            background-color: #734d26;
            color: #f8eed3;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-back:hover {
            background-color: #5a3b1e;
        }
        
        .empty-space {
            width: 80px;
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Page Title */
        .page-title {
            margin-bottom: 30px;
        }
        
        .page-title h1 {
            font-size: 28px;
            color: #734d26;
            margin: 0;
        }
        
        .page-title p {
            color: #666;
            margin: 5px 0 0 0;
        }
        
        .card { 
            background: #fbf7e9; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            margin-bottom: 20px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        
        th, td { 
            padding: 12px; 
            border-bottom: 1px solid #ddd; 
            text-align: left; 
        }
        
        th {
            background-color: #e8dcc8;
            color: #734d26;
        }
        
        .status-Approved { color: green; font-weight: bold; }
        .status-Pending { color: orange; font-weight: bold; }
        .status-Suspended, .status-Rejected { color: red; font-weight: bold; }
        
        h2 { 
            color: #734d26; 
            margin-top: 0;
        }
        
        .service-tag { 
            font-size: 0.85em; 
            background: #e8dcc8; 
            padding: 2px 6px; 
            border-radius: 4px; 
            color: #734d26;
        }
        
        .btn-accept {
            color: green;
            font-weight: bold;
            margin-right: 10px;
            text-decoration: none;
        }
        
        .btn-reject {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }
        
        .btn-suspend {
            color: red;
            text-decoration: none;
        }
        
        .btn-reapprove {
            color: green;
            text-decoration: none;
        }
        
        .no-data {
            color: #999;
            font-style: italic;
            padding: 20px;
            text-align: center;
        }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<!-- Fixed Header -->
<div class="header">
    <form action="admin_dashboard.php" style="margin: 0;">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="empty-space"></div>
</div>

<div class="container">
    <!-- Page Title -->
    <div class="page-title">
        <br><h1>Manage Technicians</h1>
        <p>Approve, suspend, or manage technician accounts</p>
    </div>

    <!-- Pending Registrations Card -->
    <div class="card">
        <h2>Pending Registrations</h2>
        <?php if (!empty($pending)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>SSM Number</th>
                        <th>Services</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['tech_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['tech_email']); ?></td>
                        <td><?php echo htmlspecialchars($row['tech_phonenum']); ?></td>
                        <td><?php echo htmlspecialchars($row['ssm_num']); ?></td>
                        <td><span class="service-tag"><?php echo htmlspecialchars($row['services_list'] ?: 'None'); ?></span></td>
                        <td>
                            <a href="manage_techs.php?action=approve&id=<?php echo $row['technician_id']; ?>" 
                               onclick="return confirm('APPROVE this technician?');" 
                               class="btn-accept">✓ Accept</a>
                            <a href="manage_techs.php?action=reject&id=<?php echo $row['technician_id']; ?>" 
                               onclick="return confirm('REJECT this technician?');" 
                               class="btn-reject">✗ Reject</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No pending registrations.</p>
        <?php endif; ?>
    </div>

    <!-- Active & Suspended Technicians Card -->
    <div class="card">
        <h2>Active & Suspended Technicians</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>SSM</th>
                    <th>Services</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($active as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['tech_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['tech_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['tech_phonenum']); ?></td>
                    <td><?php echo htmlspecialchars($row['ssm_num']); ?></td>
                    <td><span class="service-tag"><?php echo htmlspecialchars($row['services_list'] ?: 'None'); ?></span></td>
                    <td class="status-<?php echo $row['tech_status']; ?>"><?php echo $row['tech_status']; ?></td>
                    <td>
                        <?php if ($row['tech_status'] == 'Approved'): ?>
                            <a href="manage_techs.php?action=suspend&id=<?php echo $row['technician_id']; ?>" 
                               onclick="return confirm('SUSPEND this technician?');" 
                               class="btn-suspend">Suspend</a>
                        <?php else: ?>
                            <a href="manage_techs.php?action=approve&id=<?php echo $row['technician_id']; ?>" 
                               onclick="return confirm('RE-APPROVE this technician?');" 
                               class="btn-reapprove">✓ Re-Approve</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>