<?php
session_start();
include('dbconn.php');


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

// We execute the query and separate them based on status
$all_techs = $conn->query($query);

// To avoid running the query twice, we can filter in PHP
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
    <title>Manage Technicians | Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 1200px; margin: auto; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        .status-Approved { color: green; font-weight: bold; }
        .status-Pending { color: orange; font-weight: bold; }
        .status-Suspended, .status-Rejected { color: red; font-weight: bold; }
        h2 { color: #333; }
        .service-tag { font-size: 0.85em; background: #eee; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    
    <div class="card">
        <h2>Pending Registrations</h2>
        <?php if (!empty($pending)): ?>
            <table>
                <thead>
                    <tr><th>Name</th><th>Email</th><th>Phone</th><th>SSM Number</th><th>Services</th><th>Action</th></tr>
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
                               style="color: green; font-weight:bold; margin-right:10px;">Accept</a>
                            <a href="manage_techs.php?action=reject&id=<?php echo $row['technician_id']; ?>" 
                               onclick="return confirm('REJECT this technician?');" 
                               style="color: red; font-weight:bold;">Reject</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending registrations.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Active & Suspended Technicians</h2>
        <table>
            <thead>
                <tr><th>Name</th><th>Email</th><th>Phone Number</th><th>SSM</th><th>Services</th><th>Status</th><th>Action</th></tr>
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
                               onclick="return confirm('SUSPEND this technician?');" style="color: red;">Suspend</a>
                        <?php else: ?>
                            <a href="manage_techs.php?action=approve&id=<?php echo $row['technician_id']; ?>" 
                               onclick="return confirm('RE-APPROVE this technician?');" style="color: green;">Re-Approve</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>

</body>
</html>