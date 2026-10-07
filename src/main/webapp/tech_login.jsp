<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Technician Login</title>
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
        
        .nav-links a { 
            margin-left: 25px; 
            text-decoration: none; 
            color: #f8eed3;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            color: #ffd700;
        }
        
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 90px);
            padding: 20px;
            margin-top: 70px;
        }
        
        .login-card { 
            background: #fbf7e9; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 350px; 
        }
        
        .login-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #734d26;
        }
        
        input { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
            background-color: #fff;
        }
        
        .btn { 
            width: 100%; 
            padding: 12px; 
            background-color: #734d26; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #5a3b1e;
        }
        
        .error {
            color: #d9534f; 
            font-size: 14px; 
            margin-bottom: 10px; 
            text-align: center; 
            background: #f8d7da; 
            padding: 8px; 
            border-radius: 4px;
        }
        
        .register-link {text-align: center; margin-top: 15px; font-size: 13px;}
        .register-link p { color: #333; }
        .register-link a {color: #734d26; text-decoration: none; font-weight: bold;}
        .register-link a:hover {text-decoration: underline;}
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

    <div class="header">
        <form action="HouseFixController" method="GET" style="margin: 0;">
            <input type="hidden" name="action" value="home">
            <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
        </form>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div class="nav-links">
            <a href="HouseFixController?action=services"></a>
        </div>
    </div>

    <div class="center-container">
        <div class="login-card">
            <h2>Technician Login</h2>
            
            <%-- Simple Java Scriptlet replacing <c:if> --%>
            <% 
                String errorMsg = (String) request.getAttribute("errorMessage");
                if (errorMsg != null && !errorMsg.isEmpty()) { 
            %>
                <p class="error"><%= errorMsg %></p>
            <% } %>

            <form action="HouseFixController" method="POST">
                <input type="hidden" name="action" value="processTechLogin">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
            
            <div class="register-link">
                <p>Don't have an account? <a href="HouseFixController?action=showTechRegister">Register here</a></p>
            </div>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>

</body>
</html>