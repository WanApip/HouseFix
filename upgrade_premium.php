
<!DOCTYPE html>
<html>
<head>
    <title>Upgrade Your Plan | HouseFix</title>
    <style>
        .pricing-container { display: flex; gap: 20px; justify-content: center; padding: 50px; font-family: sans-serif; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 10px; width: 300px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .premium { border: 2px solid gold; background-color: #fffdf0; }
        .btn { padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn-basic { border: 1px solid #333; color: #333; }
        .btn-premium { background: gold; color: #000; font-weight: bold; }
    </style>
</head>
<body>
    <div class="pricing-container">
        <!-- Basic Card -->
        <div class="card">
            <h2>Basic</h2>
            <p><strong>Free</strong></p>
            <ul style="text-align: left;">
                <li>10 monthly jobs only</li>
                <li>Single category repair</li>
                <li>Basic profile listing</li>
            </ul>
            <a href="tech_dashboard.php" class="btn btn-basic">Stay on Basic</a>
        </div>

        <!-- Premium Card -->
        <div class="card premium">
            <h2>Premium</h2>
            <p><strong>RM 99.00</strong></p>
            <ul style="text-align: left;">
                <li>Unlimited monthly jobs</li>
                <li>Multi-category selector</li>
                <li>Advanced analytics</li>
                <li>Pro verified badge</li>
            </ul>
            <form action="payment_process.php" method="POST">
                <button type="submit" class="btn btn-premium">Subscribe to Premium</button>
            </form>
        </div>
    </div>
</body>
</html>