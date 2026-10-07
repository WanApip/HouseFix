<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
    // Ensure active technician session
    if (session.getAttribute("technician_id") == null) {
        response.sendRedirect("HouseFixController?action=showTechLogin");
        return;
    }
%>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upgrade Your Plan | HouseFix</title>
    <style>
        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            background-color: #f8eed3; 
            color: #334155; 
            margin: 0px; 
            display: flex;
            flex-direction: column;
            min-height: 100vh; 
        }

        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            width: 100%;
            box-sizing: border-box;
            padding: 100px 20px 40px 20px; 
        }
        
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            z-index: 1000; 
        }
        
        .header-logo {
            height: 60px;
            width: auto;
            display: block;
        }
        
        .pricing-container { 
            display: flex; 
            gap: 20px; 
            justify-content: center; 
            padding: 120px 50px 50px 50px; 
        }
        
        .card { 
            border: 1px solid #ddd; 
            padding: 20px; 
            border-radius: 10px; 
            width: 300px; 
            text-align: center; 
            background: #fbf7e9; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
        }
        
        .premium { border: 2px solid #ffd700; }
        
        .btn { 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-decoration: none; 
            display: inline-block; 
            cursor: pointer; 
            border: none; 
            font-weight: bold; 
        }
        
        .btn-premium { 
            background: #734d26; 
            color: #f8eed3; 
            width: 100%; 
            margin-top: 20px; 
        }
        
        /* Modal Styles */
        #modal-trigger { display: none; }
        .modal { 
            display: none; 
            position: fixed; 
            top:0; 
            left:0; 
            width:100%; 
            height:100%; 
            background:rgba(0,0,0,0.6); 
            z-index:2000; 
        }
        
        .modal-content { 
            background:white; 
            margin:10% auto; 
            padding:25px; 
            width:320px; 
            border-radius:8px; 
            text-align:center; 
        }
        
        .payment-fields { display: none; margin: 20px 0; }
        
        #qr-radio:checked ~ #qr-section { display: block; }
        #cc-radio:checked ~ #cc-section { display: block; }

        .footer { 
            background-color: #734d26; 
            color: #f8eed3; 
            text-align: center; 
            padding: 20px; 
            font-size: 14px; 
            margin-top: auto; 
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div class="header">
        <a href="HouseFixController?action=techDashboard" class="btn" style="background:#f8eed3; color:#734d26;">Back</a>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div style="width: 60px;"></div>
    </div>

    <div class="pricing-container">
        <div class="card">
            <h2>Basic</h2>
            <p><strong>Free</strong></p>
            <ul style="text-align: left;">
                <li>10 monthly jobs</li>
                <li>Single category</li>
            </ul>
            <br><br><br>
            <a href="HouseFixController?action=techDashboard" class="btn" style="background:#734d26; color:#f8eed3;">Stay on Basic</a>
        </div>

        <div class="card premium">
            <h2>Premium</h2>
            <p><strong>RM 99.00</strong><br><small>(Lifetime Access)</small></p>
            <ul style="text-align: left;">
                <li>Unlimited monthly jobs</li>
                <li>Multi-category selector</li>
                <li>Advanced analytics</li>
                <li>Pro verified badge</li>
            </ul>
            <input type="checkbox" id="modal-trigger">
            <label for="modal-trigger" onclick="document.getElementById('paymentModal').style.display='block'" class="btn" style="background:#734d26; color:#f8eed3;">Subscribe to Premium</label>
        </div>
    </div>

    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <h3>Choose Payment Method</h3>
            <form action="HouseFixController" method="POST">
                <input type="hidden" name="action" value="processPremiumUpgrade">
                
                <input type="radio" name="method" id="qr-radio" value="qr" required> QR Pay
                <input type="radio" name="method" id="cc-radio" value="cc"> Credit Card
                
                <div id="qr-section" class="payment-fields">
                    <img src="images/qr_image.jpg" alt="QR Code" style="width:150px;">
                    <p>Scan to pay RM 99.00</p>
                </div>
                
                <div id="cc-section" class="payment-fields">
                    <input type="text" name="bank_name" placeholder="Bank Name" style="width:90%; margin:5px;">
                    <input type="text" name="card_num" placeholder="Card Number" style="width:90%; margin:5px;">
                    <input type="text" name="cvv" placeholder="CVV" style="width:90%; margin:5px;">
                </div>
                
                <button type="submit" class="btn btn-premium">Proceed Payment</button>
                <label onclick="document.getElementById('paymentModal').style.display='none'" style="display:block; margin-top:10px; cursor:pointer;">Cancel</label>
            </form>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>

</body>
</html>