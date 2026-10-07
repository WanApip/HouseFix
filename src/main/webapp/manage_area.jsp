<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.AreaBean" %>

<%
    // Session Check
    if (session == null || session.getAttribute("admin_id") == null) {
        response.sendRedirect("HouseFixController?action=showAdminLogin");
        return;
    }

    List<AreaBean> areaList = (List<AreaBean>) request.getAttribute("areaList");
    String error = request.getParameter("error");
    String success = request.getParameter("success");
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HouseFix Admin | Manage Areas</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding-top: 80px; 
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
        .header-logo { height: 60px; width: auto; display: block; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; flex: 1; width: 100%; box-sizing: border-box; }
        .card { background: #fbf7e9; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1, h2 { color: #734d26; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #e8dcc8; color: #734d26; }
        input[type="text"] { padding: 8px; width: 200px; border: 1px solid #ddd; border-radius: 4px; margin-right: 5px; }
        button { padding: 8px 16px; background-color: #734d26; color: #f8eed3; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #5a3b1e; }
        
        /* Alert Message Styles */
        .alert {
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: 500;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <form action="HouseFixController" method="GET" style="margin: 0;">
        <input type="hidden" name="action" value="adminDashboard">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="empty-space"></div>
</div>

<div class="container">
    <h1>Manage Service Areas</h1>

    <!-- Display Error / Success Notifications -->
    <% if ("duplicate_postcode".equals(error)) { %>
        <div class="alert alert-error">
            ⚠️ <strong>Duplicate Postcode:</strong> The postcode you entered already exists in the system.
        </div>
    <% } else if ("failed".equals(error)) { %>
        <div class="alert alert-error">
            ⚠️ <strong>Error:</strong> Failed to add the service area. Please try again.
        </div>
    <% } else if ("added".equals(success)) { %>
        <div class="alert alert-success">
            ✓ <strong>Success:</strong> New service area added successfully!
        </div>
    <% } %>

    <div class="card">
        <h2>Add New Area</h2>
        <form action="HouseFixController" method="POST">
            <input type="hidden" name="action" value="addArea">
            <input type="text" name="postcode" placeholder="Postcode" required>
            <input type="text" name="town" placeholder="Town" required>
            <input type="text" name="state" placeholder="State" required>
            <button type="submit">Add Area</button>
        </form>
    </div>

    <div class="card">
        <h2>Current Service Areas</h2>
        <table>
            <thead>
                <tr>
                    <th>Postcode</th>
                    <th>Town</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
                <% if (areaList != null && !areaList.isEmpty()) { %>
                    <% for (AreaBean area : areaList) { %>
                        <tr>
                            <td><%= area.getPostcode() %></td>
                            <td><%= area.getTown() %></td>
                            <td><%= area.getState() %></td>
                        </tr>
                    <% } %>
                <% } else { %>
                    <tr>
                        <td colspan="3" style="text-align: center;">No service areas added yet.</td>
                    </tr>
                <% } %>
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>
</body>
</html>