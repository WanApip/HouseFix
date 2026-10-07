<?php
session_start();
include('dbconn.php');

if (!isset($_SESSION['technician_id'])) {
    header("Location: tech_login.php");
    exit();
}

$tech_id = $_SESSION['technician_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analytics | HouseFix Pro</title>
    <style>
        body { font-family: 'Inter', -apple-system, sans-serif; background-color: #f8fafc; color: #334155; padding: 40px 20px; }
        .container { max-width: 900px; margin: auto; }
        .analytics-card { background: #ffffff; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px; border: 1px solid #e2e8f0; }
        h1 { font-weight: 700; color: #1e293b; margin-bottom: 30px; }
        h3 { margin-top: 0; color: #64748b; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .earning-value { font-size: 2rem; font-weight: 800; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; color: #94a3b8; font-size: 0.8rem; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9; }
        td { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="container">
    <a href="tech_dashboard.php" class="back-link">← Return to Dashboard</a>
    <h1>Analytics Overview</h1>

    <div class="analytics-card" style="background: #0f172a; color: #fff;">
        <h3 style="color: #94a3b8;">Total Revenue</h3>
        <?php
        $query_total = "SELECT SUM(S.services_fee) as rev 
                        FROM BOOKING B 
                        JOIN booking_detail BD ON B.booking_id = BD.booking_id
                        JOIN SERVICES S ON BD.services_id = S.services_id 
                        WHERE B.technician_id = $tech_id AND B.booking_status = 'Completed'";
        $result_total = $conn->query($query_total);
        $total = $result_total ? $result_total->fetch_assoc() : ['rev' => 0];
        ?>
        <div class="earning-value" style="color: #94a3b8;">RM <?php echo number_format($total['rev'] ?? 0, 2); ?></div>
    </div>

    <div class="analytics-card">
        <h3>Performance by Category</h3>
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Jobs Completed</th>
                    <th>Total Earnings (RM)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT S.services_name, COUNT(B.booking_id) as total_jobs, SUM(S.services_fee) as total_inc 
                    FROM BOOKING B 
                    JOIN booking_detail BD ON B.booking_id = BD.booking_id
                    JOIN SERVICES S ON BD.services_id = S.services_id
                    WHERE B.technician_id = $tech_id AND B.booking_status = 'Completed'
                    GROUP BY S.services_id";
            
            $income = $conn->query($sql);

            if ($income && $income->num_rows > 0) {
                while($row = $income->fetch_assoc()) {
                    echo "<tr>
                            <td><strong>{$row['services_name']}</strong></td>
                            <td>{$row['total_jobs']}</td>
                            <td style='font-weight: 600;'>RM " . number_format($row['total_inc'], 2) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No completed jobs found to display.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>