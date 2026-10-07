<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
    String bookingIdStr = request.getParameter("booking_id");
    if (bookingIdStr == null && request.getAttribute("bookingId") != null) {
        bookingIdStr = String.valueOf(request.getAttribute("bookingId"));
    }
    int bookingId = (bookingIdStr != null && !bookingIdStr.isEmpty()) ? Integer.parseInt(bookingIdStr) : 0;
    boolean isSuccess = "true".equals(request.getParameter("success"));
%>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HouseFix | Rate Your Service</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
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

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            padding: 100px 20px 20px 20px;
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
            font-weight: bold; 
            margin-top: 20px;
        }
        .btn:hover { background-color: #5a3b1e; }
        
        .card { 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 90%; 
            max-width: 400px; 
            text-align: center; 
        }
        h3 { color: #333; margin-top: 0; }
        input[type="number"], textarea { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-sizing: border-box; 
        }
        .success-msg { color: #28a745; font-weight: bold; }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 30px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <form action="HouseFixController" method="GET" style="margin: 0;">
        <input type="hidden" name="action" value="showHomePage">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Home</button>
    </form>
    
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div style="width: 80px;"></div>
</div>

<div class="center-container">
    <div class="card">
        <% if (isSuccess) { %>
            <div class="success-msg">✅ Thank you! Your feedback helps us improve.</div>
            <br>
            <a href="HouseFixController?action=home" style="color: #734d26; font-weight: bold; text-decoration: none;">Return to Home</a>
        <% } else { %>
            <h3>Rate Your HouseFix Service</h3>
            <form action="HouseFixController" method="POST">
                <input type="hidden" name="action" value="submitReview">
                <input type="hidden" name="booking_id" value="<%= bookingId %>">
                
                <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
                <textarea name="comment" rows="4" placeholder="How was the service?" required></textarea>
                
                <button type="submit" class="btn">Submit Feedback</button>
            </form>
        <% } %>
    </div>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>
</body>
</html>