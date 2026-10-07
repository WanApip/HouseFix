<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.AreaBean" %>
<%@ page import="housefix.ServiceBean" %>
<%
    List<AreaBean> areaList = (List<AreaBean>) request.getAttribute("areaList");
    List<ServiceBean> serviceList = (List<ServiceBean>) request.getAttribute("serviceList");
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Technician Registration</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8eed3; }
        
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #ddd; 
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }
        
        .header-logo { height: 60px; width: auto; display: block; }
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #f8eed3; }
        .nav-links a:hover { color: #ffd700; }
        
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
            margin-top: 60px;
        }
        
        .register-card {
            background: #fbf7e9;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        
        .register-card h2 { text-align: center; margin-bottom: 25px; color: #734d26; }
        
        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fff;
        }
        
        input:focus, select:focus { outline: none; border-color: #734d26; }
        
        label {
            font-size: 14px;
            font-weight: bold;
            color: #734d26;
            display: block;
            margin-top: 10px;
        }
        
        .btn { width: 100%; padding: 12px; background-color: #734d26; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 20px;}
        .btn:hover { background-color: #5a3b1e;}
        .login-link { text-align: center; margin-top: 20px; font-size: 13px;}
        .login-link p { color: #333; }
        .login-link a { color: #734d26; text-decoration: none; font-weight: bold;}
        .login-link a:hover { text-decoration: underline;}

        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<!-- FIXED HEADER -->
<div class="header">
    <form action="HouseFixController" method="GET" style="margin: 0;">
        <input type="hidden" name="action" value="home">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    
    <div class="nav-links">
        <a href="HouseFixController?action=techLogin" style="color: #f8eed3;">Login</a>
    </div>
</div>

<!-- REGISTRATION FORM -->
<div class="register-container">
    <div class="register-card">
        <h2>Join HouseFix as a Technician</h2>
        
        <form action="HouseFixController" method="POST">
            <input type="hidden" name="action" value="processTechRegister">
            
            <input type="text" name="tech_name" placeholder="Full Name" required>
            <input type="email" name="tech_email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="tech_phonenum" placeholder="Phone Number" required>
            <input type="text" name="ssm_num" placeholder="SSM Number" required>
            
            <label>Select Your Working Area:</label>
            <select name="area_id" required>
                <option value="">-- Select Town --</option>
                <% 
                if (areaList != null) { 
                    for (AreaBean a : areaList) { 
                %>
                        <option value="<%= a.getAreaId() %>"><%= a.getTown() %></option>
                <% 
                    } 
                } 
                %>
            </select>
            
            <label>Expert Job:</label>
            <select name="services_id" required>
                <option value="">-- Select Service --</option>
                <% 
                if (serviceList != null) { 
                    for (ServiceBean s : serviceList) { 
                %>
                        <option value="<%= s.getServicesId() %>"><%= s.getServicesName() %></option>
                <% 
                    } 
                } 
                %>
            </select>
            
            <button type="submit" class="btn">Submit Application</button>
        </form>
         
        <div class="login-link">
            <p>Already have an account? <a href="HouseFixController?action=tech_login">Login here</a></p>
        </div>
    </div>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>

</body>
</html>