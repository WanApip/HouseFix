<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="housefix.BookingBean" %>
<%
    BookingBean booking = (BookingBean) request.getAttribute("booking");
    if (booking == null) {
        response.sendRedirect("HouseFixController?action=showServices");
        return;
    }
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Track Your Fixer</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f8eed3; 
            display: flex;
            flex-direction: column;
            padding-top: 90px;
            box-sizing: border-box;
            min-height: 100vh;
            margin: 0;
        }
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
        .container { 
            padding: 40px; 
            max-width: 600px; 
            margin: 0 auto; 
            text-align: center; 
            flex: 1 0 auto; 
        }
        .status-box { border: 1px solid #ccc; padding: 30px; border-radius: 8px; background-color: #fbf7e9; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .status-banner { display: inline-block; padding: 10px 20px; font-weight: bold; border-radius: 20px; font-size: 18px; text-transform: uppercase; margin: 15px 0; }
        
        .status-pending { background-color: #ffeeba; color: #856404; }
        .status-progress { background-color: #b8daff; color: #004085; }
        .status-completed { background-color: #c3e6cb; color: #155724; }
        
        .btn-rate { display: inline-block; background-color: #734d26; color: #f8eed3; padding: 12px 24px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; text-decoration: none; font-weight: bold; margin-top: 20px; width: 80%; text-align: center; }
        .btn-rate:hover { background-color: #5a3b1e; }
        .btn-locked { background-color: #e9ecef; color: #adb5bd; padding: 12px 24px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; cursor: not-allowed; width: 85%; margin-top: 20px; font-weight: bold; }
        h2, .status-box h3 { color: #734d26; }

        .footer { 
            background-color: #734d26; 
            color: #f8eed3; 
            text-align: center; 
            padding: 30px; 
            flex-shrink: 0; 
            font-size: 14px; 
            width: 100%;
            box-sizing: border-box;
        }
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
            <a href="HouseFixController?action=techLogin" style="color: #f8eed3;"></a>
        </div>
    </div>

    <div class="container">
        <h2>Booking Reference: #<%= booking.getBookingId() %></h2>
        
        <div class="status-box">
            <h3>Assigned Fixer: <%= request.getAttribute("techName") != null ? request.getAttribute("techName") : "N/A" %></h3>
            <p>Scheduled Time: <strong><%= booking.getBookingDate() %> @ <%= booking.getBookingTime() %></strong></p>
            <hr style="border: 0; border-top: 1px solid #ddd; margin: 20px 0;">
            
            <p style="color: #555; font-size: 15px;">Live Job Status:</p>
            
            <% 
            String status = booking.getBookingStatus();
            if ("Pending".equalsIgnoreCase(status)) { 
            %>
                <span class="status-banner status-pending">Pending</span>
                <p style="color:#777; font-size: 14px;">Waiting for the technician to complete your repair job.</p>
            <% } else if ("In Progress".equalsIgnoreCase(status)) { %>
                <span class="status-banner status-progress">In Progress</span>
                <p style="color:#777; font-size: 14px;">The technician is currently on-site working on your issue.</p>
            <% } else if ("Completed".equalsIgnoreCase(status)) { %>
                <span class="status-banner status-completed">Completed</span>
                <p style="color:#28a745; font-weight:bold; font-size: 14px;">Your maintenance issue has been completely fixed!</p>
            <% } else { %>
                <span class="status-banner status-pending"><%= status %></span>
            <% } %>

            <hr style="border: 0; border-top: 1px solid #ddd; margin: 25px 0;">

            <% if ("Completed".equalsIgnoreCase(status)) { %>
                <a href="HouseFixController?action=showReviewForm&booking_id=<%= booking.getBookingId() %>" class="btn-rate">⭐ Leave a Review / Rating</a>
            <% } else { %>
                <button class="btn-locked" disabled>🔒 Review Blocked (Available Once Job is Completed)</button>
            <% } %>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>

</body>
</html>