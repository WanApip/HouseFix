<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.BookingBean" %>

<%
    // Session Check
    if (session == null || session.getAttribute("admin_id") == null) {
        response.sendRedirect("HouseFixController?action=showAdminLogin");
        return;
    }

    List<BookingBean> pending = (List<BookingBean>) request.getAttribute("pendingList");
    List<BookingBean> completed = (List<BookingBean>) request.getAttribute("completedList");
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HouseFix Admin | View Bookings</title>
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
        .card { background: #fbf7e9; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        h1, h2 { color: #734d26; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #e8dcc8; color: #734d26; }
        .status-Completed { color: green; font-weight: bold; }
        .status-Pending { color: orange; font-weight: bold; }
        button { padding: 8px 16px; background-color: #734d26; color: #f8eed3; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #5a3b1e; }
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
    <h1>Booking Overview</h1>

    <div class="card">
        <h2>Pending Bookings</h2>
        <% if (pending == null || pending.isEmpty()) { %>
            <p>No pending bookings.</p>
        <% } else { %>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Contact Info</th>
                        <th>Services</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <% for (BookingBean row : pending) { %>
                        <tr>
                            <td><%= row.getBookingDate() %></td>
                            <td><%= row.getCustomerName() %></td>
                            <td>
                                <%= row.getCustomerPhonenum() %><br>
                                <small style="color: #666;"><%= row.getCustomerEmail() %></small>
                            </td>
                            <td><%= row.getServicesList() != null ? row.getServicesList() : "N/A" %></td>
                            <td class="status-Pending">Pending</td>
                        </tr>
                    <% } %>
                </tbody>
            </table>
        <% } %>
    </div>

    <div class="card">
        <h2>Completed Bookings</h2>
        <% if (completed == null || completed.isEmpty()) { %>
            <p>No completed bookings found.</p>
        <% } else { %>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Contact Info</th>
                        <th>Services</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <% for (BookingBean row : completed) { %>
                        <tr>
                            <td><%= row.getBookingDate() %></td>
                            <td><%= row.getCustomerName() %></td>
                            <td>
                                <%= row.getCustomerPhonenum() %><br>
                                <small style="color: #666;"><%= row.getCustomerEmail() %></small>
                            </td>
                            <td><%= row.getServicesList() != null ? row.getServicesList() : "N/A" %></td>
                            <td class="status-Completed">Completed</td>
                        </tr>
                    <% } %>
                </tbody>
            </table>
        <% } %>
    </div>
</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>
</body>
</html>