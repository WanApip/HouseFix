<?php
include('dbconn.php');

// Ensure we have a valid booking ID
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if (isset($_POST['submit_review'])) {
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment']);

    // Update review
    $conn->query("INSERT INTO REVIEWS (booking_id, rating_score, review_comment) VALUES ($booking_id, $rating, '$comment')");
    
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HouseFix | Rate Your Service</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .header { 
            background-color: #FAF3E0; 
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
        
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; }
        
        /* Center the card */
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 20px;
        }
        
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 90%; max-width: 400px; text-align: center; }
        h3 { color: #333; margin-top: 0; }
        input[type="number"], textarea { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 12px; border-radius: 8px; width: 100%; cursor: pointer; font-weight: bold; }
        button:hover { background: #0056b3; }
        .success-msg { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>

        <!-- FIXED HEADER - Stays on top when scrolling -->
<div class="header">
    <form action="services.php" style="margin: 0;">
        <button type="submit" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <div class="logo">HouseFix</div>
    <div class="nav-links">
        <a href="homepage.php">Home</a>
    </div>
</div>

<div class="center-container">
    <div class="card">
        <?php if (isset($success)): ?>
            <div class="success-msg">✅ Thank you! Your feedback helps us improve.</div>
            <br><a href="homePage.php">Return to Home</a>
        <?php else: ?>
            <h3>Rate Your HouseFix Service</h3>
            <form method="POST">
                <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
                <textarea name="comment" placeholder="How was the service?" required></textarea>
                <button type="submit" name="submit_review">Submit Feedback</button>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>