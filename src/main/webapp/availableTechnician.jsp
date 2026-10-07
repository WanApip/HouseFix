<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.TechnicianBean" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Available Technicians</title>
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f8eed3;
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
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }
        .header-logo { height: 60px; width: auto; }
        .container { 
            padding: 40px; 
            max-width: 850px; 
            margin: 90px auto 0 auto; 
            flex: 1;
            width: 100%;
        }
        .section-title { font-size: 28px; margin-bottom: 10px; text-align: center; color: #333; }
        .section-subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .tech-list { display: flex; flex-direction: column; gap: 20px; }
        .tech-card { 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            padding: 20px; 
            background-color: #fbf7e9; 
            display: flex; 
            align-items: center;
            gap: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        }
        .tech-card.premium-card { border: 2px solid #ffd700; background-color: #fffdf5; }
        .tech-avatar { 
            width: 80px; 
            height: 80px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 2px solid #734d26; 
        }
        .tech-info { flex: 1; }
        .tech-info h3 { margin: 0 0 5px 0; color: #333; display: flex; align-items: center; gap: 10px; }
        .tech-info p { margin: 3px 0; color: #666; font-size: 14px; }
        .status-badge { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .premium-badge { background-color: #ffd700; color: #333; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; border: 1px solid #e6c200; }
        .btn-book { background-color: #734d26; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; text-decoration: none; }
        .btn-book:hover { background-color: #5a3b1e; }
        .no-results { text-align: center; padding: 40px; border: 1px dashed #ccc; border-radius: 8px; background: #fbf7e9; }
        .btn-back { display: inline-block; margin-top: 15px; color: #734d26; text-decoration: none; font-weight: bold; }
        .btn-back:hover { text-decoration: underline; }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; font-size: 14px; margin-top: auto; }
    </style>
</head>
<body>

<div class="header">
    <a href="HouseFixController?action=showServices" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border-radius: 5px; text-decoration: none;">Back</a>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="nav-links"></div>
</div>

<div class="container">
    <h2 class="section-title">Available Technicians</h2>
    <p class="section-subtitle">Showing local experts available to fix your problem right now.</p>

    <div class="tech-list">
        <% 
            @SuppressWarnings("unchecked")
            List<TechnicianBean> technicians = (List<TechnicianBean>) request.getAttribute("techniciansList");
            
            if (technicians != null && !technicians.isEmpty()) {
                for (TechnicianBean tech : technicians) {
                    boolean isPremium = "Premium".equalsIgnoreCase(tech.getPlanType());
                    String cardClass = isPremium ? "tech-card premium-card" : "tech-card";
                    
                    // Fallback to default image if photo_path is missing
                    String photo = (tech.getPhotoPath() != null && !tech.getPhotoPath().trim().isEmpty()) 
                                   ? tech.getPhotoPath() : "images/default-avatar.png";
        %>
            <div class="<%= cardClass %>">
                <img src="<%= photo %>" alt="<%= tech.getTechName() %>" class="tech-avatar">
                
                <div class="tech-info">
                    <h3>
                        <%= tech.getTechName() %>
                        <% if (isPremium) { %>
                            <span class="premium-badge">⭐ PREMIUM</span>
                        <% } %>
                    </h3>
                    <p>Phone: <%= tech.getTechPhonenum() %></p>
                    <p>Email: <%= tech.getTechEmail() %></p>
                    <p>Status: <span class="status-badge"><%= tech.getTechAvailability() %></span></p>
                </div>
                
                <div>
                    <form action="HouseFixController" method="POST">
                        <input type="hidden" name="action" value="selectTechnician">
                        <input type="hidden" name="technician_id" value="<%= tech.getTechnicianId() %>">
                        <button type="submit" class="btn-book">Book Technician</button>
                    </form>
                </div>
            </div>
        <% 
                }
            } else { 
        %>
            <div class="no-results">
                <h3>No available technicians found in your selected area.</h3>
                <p>Try selecting a different service category or location area.</p>
                <a href="HouseFixController?action=showServices" class="btn-back">← Go Back to Selection</a>
            </div>
        <% 
            } 
        %>
    </div>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>

</body>
</html>