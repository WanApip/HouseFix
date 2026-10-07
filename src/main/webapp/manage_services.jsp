<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.ServiceBean" %>

<%
    // Ensure active session
    if (session.getAttribute("technician_id") == null) {
        response.sendRedirect("HouseFixController?action=showTechLogin");
        return;
    }

    List<ServiceBean> allServices = (List<ServiceBean>) request.getAttribute("allServices");
    List<ServiceBean> techServices = (List<ServiceBean>) request.getAttribute("techServices");
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Manage Specialties</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding: 0; 
            padding-top: 80px;
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
        
        .header-logo {
            height: 60px;
            width: auto; 
            display: block;
        }

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
        
        .btn-back:hover {
            background-color: #e5d7b5;
        }
        
        .container { 
            max-width: 500px; 
            margin: 50px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        
        h2 { margin-top: 0; }
        input[type="checkbox"] { margin: 10px 5px; }
        
        .btn { 
            width: 100%; 
            padding: 12px; 
            background-color: #734d26; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold; 
            margin-top: 20px;
        }
        
        .btn:hover { background-color: #5a3b1e; }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <a href="HouseFixController?action=techDashboard" class="btn-back">Back</a>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div style="width: 60px;"></div>
</div>

<div class="container">
    <h2>Select Your Service Categories</h2>
    <form action="HouseFixController" method="POST">
        <input type="hidden" name="action" value="updateServices">
        
        <% 
            if (allServices != null) {
                for (ServiceBean s : allServices) {
                    boolean isChecked = false;
                    if (techServices != null) {
                        for (ServiceBean ts : techServices) {
                            if (ts.getServicesId() == s.getServicesId()) {
                                isChecked = true;
                                break;
                            }
                        }
                    }
        %>
            <div>
                <input type="checkbox" 
                       id="service_<%= s.getServicesId() %>" 
                       name="services" 
                       value="<%= s.getServicesId() %>" 
                       <%= isChecked ? "checked" : "" %>> 
                <label for="service_<%= s.getServicesId() %>"><%= s.getServicesName() %></label>
            </div>
        <% 
                }
            } 
        %>
        
        <button type="submit" class="btn">Update Specialties</button>
    </form>
</div>

<br><br><br><br><br><br><br>
<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>

</body>
</html>