<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="housefix.TechnicianBean" %>

<%
    // Fetch technician object from request scope or fallback to session
    TechnicianBean tech = (TechnicianBean) request.getAttribute("tech");
    if (tech == null) {
        tech = (TechnicianBean) session.getAttribute("tech");
    }

    String success = (String) request.getAttribute("success");
    String error = (String) request.getAttribute("error");
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Edit Profile</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f8eed3; 
            margin: 0; 
            padding: 0; 
            padding-top: 80px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: center;
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
        
        .container { 
            max-width: 500px; 
            margin: 30px auto; 
            background: #fbf7e9; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        
        h2 { margin-top: 0; color: #734d26; }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #734d26;
        }
        
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #fff;
        }
        
        input[readonly] {
            background-color: #e8dcc8;
            cursor: not-allowed;
        }
        
        .current-photo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-photo img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #734d26;
        }
        
        button {
            background: #734d26;
            color: #f8eed3;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
        
        button:hover {
            background: #5a3b1e;
        }
        
        .btn-back {
            background: #734d26;
            margin-top: 10px;
        }
        
        .btn-back:hover {
            background: #5a3b1e;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info-text {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
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

<div class="header">
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
</div>

<div class="container">
    <h2>Edit Profile</h2>
    
    <% if (success != null && !success.isEmpty()) { %>
        <div class="success">✅ <%= success %></div>
    <% } %>
    
    <% if (error != null && !error.isEmpty()) { %>
        <div class="error">❌ <%= error %></div>
    <% } %>
    
    <form action="HouseFixController?action=updateProfile" method="POST" enctype="multipart/form-data">
        <div class="current-photo">
            <% 
                String photo = (tech != null && tech.getPhotoPath() != null && !tech.getPhotoPath().trim().isEmpty())
                               ? tech.getPhotoPath()
                               : "uploads/technician.png";
            %>
            <img src="<%= photo %>" alt="Current Photo">
            <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
        </div>
        
        <div class="form-group">
            <label>Upload New Photo (Optional)</label>
            <input type="file" name="photo" accept="image/*">
        </div>
        
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="tech_name" value="<%= (tech != null && tech.getTechName() != null) ? tech.getTechName() : "" %>" required>
        </div>
        
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="tech_phonenum" value="<%= (tech != null && tech.getTechPhonenum() != null) ? tech.getTechPhonenum() : "" %>" required>
        </div>
        
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="tech_email" value="<%= (tech != null && tech.getTechEmail() != null) ? tech.getTechEmail() : "" %>" readonly>
            <div class="info-text">Email address cannot be changed.</div>
        </div>
        
        <button type="submit">Save Changes</button>
    </form>
    
    <form action="HouseFixController" method="GET">
        <input type="hidden" name="action" value="techDashboard">
        <button type="submit" class="btn-back">Back to Dashboard</button>
    </form>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>

</body>
</html>