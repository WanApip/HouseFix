<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
    if (session.getAttribute("technician_id") == null) {
        response.sendRedirect("HouseFixController?action=showTechLogin");
        return;
    }
%>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Successful | HouseFix</title>
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

        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            width: 100%;
            box-sizing: border-box;
            padding: 120px 20px 40px 20px; 
            text-align: center;
        }

        .btn-dashboard {
            padding: 12px 24px; 
            background: #734d26; 
            color: #f8eed3; 
            text-decoration: none; 
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 15px;
        }

        .btn-dashboard:hover {
            background: #5a3b1e;
        }

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
        <div style="width: 60px;"></div>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div style="width: 60px;"></div>
    </div>

    <div class="container">
        <h1 style="color: #2e7d32; margin-bottom: 10px;">🎉 Payment Successful!</h1>
        <p style="font-size: 18px;">Your account has been upgraded to the <strong>Premium Plan</strong>.</p>
        <p>You now have access to advanced analytics, multi-category jobs, and unlimited monthly requests.</p>
        <br>
        <a href="HouseFixController?action=techDashboard" class="btn-dashboard">
            Go to Dashboard
        </a>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>

</body>
</html>