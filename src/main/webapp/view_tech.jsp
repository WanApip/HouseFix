<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.ServiceGroupBean" %>
<%@ page import="housefix.TechnicianBean" %>

<%
    // Check Session Security
    if (session.getAttribute("admin_id") == null) {
        response.sendRedirect("HouseFixController?action=showAdminLogin");
        return;
    }

    List<ServiceGroupBean> servicesDirectory = (List<ServiceGroupBean>) request.getAttribute("servicesDirectory");
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HouseFix Admin | Services Directory</title>
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
            background-color: #e8dcc8;
        }
        
        .empty-space {
            width: 80px;
        }
        
        .container { 
            max-width: 1200px; 
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }
        
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
        
        .service-group {
            margin-bottom: 30px;
            background: #fbf7e9;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .service-group h2 {
            color: #734d26;
            border-bottom: 3px solid #734d26;
            padding-bottom: 10px;
            margin-top: 0;
            font-size: 22px;
        }
        
        .tech-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        
        .tech-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            width: 120px;
            transition: all 0.2s;
            border: 1px solid #e0e0e0;
        }
        
        .tech-card:hover {
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            transform: scale(1.02);
        }
        
        .tech-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #734d26;
            margin-bottom: 10px;
            background-color: #e9ecef;
        }
        
        .tech-name {
            font-size: 14px;
            font-weight: bold;
            color: #734d26;
            margin: 0;
            word-wrap: break-word;
        }
        
        .no-tech {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
        
        .no-services {
            text-align: center; 
            padding: 50px; 
            background: #fbf7e9; 
            border-radius: 10px;
            color: #666;
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

    <!-- Header -->
    <div class="header">
        <a href="HouseFixController?action=adminDashboard" class="btn-back">Back</a>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div class="empty-space"></div>
    </div>

    <div class="container">
        <!-- Page Title -->
        <div class="page-title">
            <br><h1>Services Directory</h1>
            <p>View all technicians grouped by their service categories</p>
        </div>

        <% if (servicesDirectory != null && !servicesDirectory.isEmpty()) { %>
            <% for (ServiceGroupBean service : servicesDirectory) { %>
                <div class="service-group">
                    <h2><%= service.getServiceName() %></h2>
                    
                    <% if (service.getTechnicians() != null && !service.getTechnicians().isEmpty()) { %>
                        <div class="tech-wrapper">
                            <% for (TechnicianBean tech : service.getTechnicians()) { %>
                                <div class="tech-card">
                                    <% if (tech.getPhotoPath() != null && !tech.getPhotoPath().trim().isEmpty()) { %>
                                        <img src="<%= tech.getPhotoPath() %>" alt="<%= tech.getTechName() %>" class="tech-photo">
                                    <% } else { %>
                                        <img src="uploads/technician.png" alt="Default Avatar" class="tech-photo">
                                    <% } %>
                                    <p class="tech-name"><%= tech.getTechName() %></p>
                                </div>
                            <% } %>
                        </div>
                    <% } else { %>
                        <p class="no-tech">No approved technicians for this service yet.</p>
                    <% } %>
                </div>
            <% } %>
        <% } else { %>
            <div class="no-services">
                <p>No services found.</p>
            </div>
        <% } %>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>

</body>
</html>