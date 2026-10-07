<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.CategoryAnalyticsBean" %>

<%
    // Ensure active technician session
    if (session.getAttribute("technician_id") == null) {
        response.sendRedirect("HouseFixController?action=showTechLogin");
        return;
    }

    Double totalRevenueObj = (Double) request.getAttribute("totalRevenue");
    double totalRevenue = (totalRevenueObj != null) ? totalRevenueObj : 0.0;

    List<CategoryAnalyticsBean> categoryAnalytics = (List<CategoryAnalyticsBean>) request.getAttribute("categoryAnalytics");
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Analytics | HouseFix Pro</title>
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
            border-bottom: 1px solid #ddd; 
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            width: 100%;
            box-sizing: border-box;
            padding: 100px 20px 40px 20px; 
        }

        .header-logo {
            height: 60px; 
            width: auto;   
            display: block;
        }

        .nav-links a { margin-left: 20px; text-decoration: none; color: #f8eed3; }
        .nav-links a:hover { color: #ffd700; }

        .analytics-card { 
            background: #fbf7e9; 
            border-radius: 12px; 
            padding: 24px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
            margin-bottom: 24px; 
            border: 1px solid #e2e8f0; 
        }

        h1 { font-weight: 700; color: #734d26; margin-bottom: 30px; }
        h3 { margin-top: 0; color: #734d26; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; }

        .earning-value { font-size: 2rem; font-weight: 800; color: #0f172a; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; color: #734d26; font-size: 0.8rem; padding-bottom: 10px; border-bottom: 2px solid #e8dcc8; }
        td { padding: 12px 0; border-bottom: 1px solid #e8dcc8; }

        .btn-back {
            padding: 8px 16px; 
            background-color: #f8eed3; 
            font-weight: bold; 
            color: #734d26; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
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
    <a href="HouseFixController?action=techDashboard" class="btn-back">Back</a>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="nav-links"></div>
</div>

<div class="container">
    <h1>Analytics Overview</h1>
    
    <div class="analytics-card" style="background: #734d26; color: #f8eed3;">
        <h3 style="color: #f8eed3;">Total Revenue</h3>
        <div class="earning-value" style="color: #ffd700;">
            RM <%= String.format("%.2f", totalRevenue) %>
        </div>
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
            <% 
                if (categoryAnalytics != null && !categoryAnalytics.isEmpty()) {
                    for (CategoryAnalyticsBean row : categoryAnalytics) {
            %>
                <tr>
                    <td><strong><%= row.getServiceName() %></strong></td>
                    <td><%= row.getTotalJobs() %></td>
                    <td style="font-weight: 600;">RM <%= String.format("%.2f", row.getTotalIncome()) %></td>
                </tr>
            <% 
                    }
                } else { 
            %>
                <tr>
                    <td colspan="3">No completed jobs found to display.</td>
                </tr>
            <% 
                } 
            %>
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>

</body>
</html>