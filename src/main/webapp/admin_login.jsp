<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Admin Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            background: #f8eed3; 
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
        }
        
        .header-logo {
            height: 60px;
            width: auto;
            display: block;
        }
        
        .center-container { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            flex: 1; 
        }
        
        .login-card { 
            background: #fbf7e9; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 350px; 
        }
        
        h2 { text-align: center; color: #734d26; }
        
        input { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
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
        
        .error { 
            color: #d9534f; 
            font-size: 14px; 
            text-align: center; 
            background: #f8d7da; 
            padding: 8px; 
            border-radius: 4px; 
        }
        
        .footer { 
            background-color: #734d26; 
            color: #f8eed3; 
            text-align: center; 
            padding: 20px; 
            font-size: 14px; 
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
    </style>
</head>
<body>

    <div class="header">
        <a href="HouseFixController?action=home" class="btn-back">Back</a>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div style="width: 60px;"></div>
    </div>

    <div class="center-container">
        <div class="login-card">
            <h2>Admin Login</h2>
            
            <% 
                String errorMessage = (String) request.getAttribute("errorMessage");
                if (errorMessage != null && !errorMessage.isEmpty()) { 
            %>
                <p class="error"><%= errorMessage %></p>
            <% } %>

            <form action="HouseFixController" method="POST">
                <input type="hidden" name="action" value="processAdminLogin">
                
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>

</body>
</html>