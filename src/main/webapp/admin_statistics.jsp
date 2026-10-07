<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
    // Security Session Check
    if (session == null || session.getAttribute("admin_id") == null) {
        response.sendRedirect("HouseFixController?action=showAdminLogin");
        return;
    }

    // Retrieve values from Request
    Double premiumRevenue = (Double) request.getAttribute("premiumRevenue");
    Double webUsageRevenue = (Double) request.getAttribute("webUsageRevenue");
    Double totalRevenue = (Double) request.getAttribute("totalRevenue");
    
    String serviceLabelsJson = (String) request.getAttribute("serviceLabelsJson");
    String serviceCountsJson = (String) request.getAttribute("serviceCountsJson");

    // Fallbacks if attributes are null
    if (premiumRevenue == null) premiumRevenue = 0.0;
    if (webUsageRevenue == null) webUsageRevenue = 0.0;
    if (totalRevenue == null) totalRevenue = 0.0;
    if (serviceLabelsJson == null) serviceLabelsJson = "[]";
    if (serviceCountsJson == null) serviceCountsJson = "[]";
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Statistics | HouseFix Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding: 0; 
            padding-top: 80px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            height: 60px;
            width: auto; 
            display: block;
        }
        
        .empty-space {
            width: 80px;
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
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
        
        /* Revenue Summary */
        .revenue-summary {
            background: linear-gradient(135deg, #734d26, #5a3b1e);
            color: #f8eed3;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .revenue-summary h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #f8eed3;
        }
        
        .revenue-summary .total {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            color: #ffd700;
        }
        
        /* Card */
        .card { 
            background: #fbf7e9; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            text-align: center;
        }
        
        .chart-container { 
            width: 400px; 
            margin: 20px; 
            display: inline-block; 
            vertical-align: top; 
        }
        
        .chart-container h3 {
            color: #734d26;
            margin-bottom: 15px;
        }
        
        .charts-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .footer { 
            background-color: #734d26; 
            color: #f8eed3; 
            text-align: center; 
            padding: 20px; 
            margin-top: auto; 
            font-size: 14px; 
        }
    </style>
</head>
<body>

<!-- Fixed Header -->
<div class="header">
    <form action="HouseFixController" method="GET" style="margin: 0;">
        <input type="hidden" name="action" value="adminDashboard">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="empty-space"></div>
</div>

<div class="container">
    <!-- Page Title -->
    <div class="page-title">
        <br><h1>Platform Statistics</h1>
        <p>View revenue breakdown and service demand analytics</p>
    </div>

    <!-- Revenue Summary -->
    <div class="revenue-summary">
        <h3>Total Revenue</h3>
        <p class="total">RM <%= String.format("%.2f", totalRevenue) %></p>
        <p style="margin: 5px 0 0 0; font-size: 14px;">
            Premium Subscriptions: RM <%= String.format("%.2f", premiumRevenue) %> | 
            Web Usage Fee: RM <%= String.format("%.2f", webUsageRevenue) %>
        </p>
    </div>

    <!-- Charts Card -->
    <div class="card">
        <div class="charts-wrapper">
            <div class="chart-container">
                <h3>Revenue Breakdown (RM)</h3>
                <canvas id="revenueChart"></canvas>
            </div>

            <div class="chart-container">
                <h3>Service Demand</h3>
                <canvas id="demandChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Revenue Pie Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'pie',
        data: {
            labels: ['Premium Subscription', 'Web Usage Fee'],
            datasets: [{ 
                data: [<%= premiumRevenue %>, <%= webUsageRevenue %>], 
                backgroundColor: ['#734d26', '#ffd700'] 
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 2. Service Demand Pie Chart
    new Chart(document.getElementById('demandChart'), {
        type: 'pie',
        data: {
            labels: <%= serviceLabelsJson %>,
            datasets: [{ 
                data: <%= serviceCountsJson %>, 
                backgroundColor: ['#734d26', '#ffd700', '#e8dcc8', '#5a3b1e', '#c9cbcf', '#36a2eb', '#ffce56'] 
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>
</body>
</html>