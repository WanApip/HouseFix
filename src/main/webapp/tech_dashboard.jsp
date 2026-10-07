<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.TechnicianBean" %>
<%@ page import="housefix.BookingBean" %>
<%@ page import="housefix.ReviewBean" %>
<%@ page import="housefix.ServiceBean" %>

<%
    TechnicianBean tech = (TechnicianBean) request.getAttribute("tech");
    String locationDisplay = (String) request.getAttribute("locationDisplay");
    Integer monthJobs = (Integer) request.getAttribute("monthJobs");
    Integer jobLimit = (Integer) request.getAttribute("jobLimit");
    Boolean limitReached = (Boolean) request.getAttribute("limitReached");
    
    List<BookingBean> assignedJobsList = (List<BookingBean>) request.getAttribute("assignedJobsList");
    List<ReviewBean> reviewsList = (List<ReviewBean>) request.getAttribute("reviewsList");
    List<ServiceBean> servicesList = (List<ServiceBean>) request.getAttribute("servicesList");

    if (monthJobs == null) monthJobs = 0;
    if (jobLimit == null) jobLimit = 10;
    if (locationDisplay == null) locationDisplay = "Not Specified";
    if (limitReached == null) limitReached = false;

    boolean isBasic = tech != null && "Basic".equalsIgnoreCase(tech.getPlanType());
    boolean isPremium = tech != null && "Premium".equalsIgnoreCase(tech.getPlanType());
    boolean isAvailable = tech != null && "Available".equalsIgnoreCase(tech.getTechStatus());
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Technician Dashboard</title>
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
        
        .header-logo { height: 60px; width: auto; display: block; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #f8eed3; }
        .nav-links a:hover { color: #ffd700; }
        
        .dashboard-container { max-width: 1000px; margin: auto; }
        
        .headerWelcome { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: #fbf7e9; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            margin-top: 20px;
        }
        
        .card { 
            background: #fbf7e9; 
            padding: 20px; 
            border-radius: 10px; 
            margin-top: 20px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        
        .premium { 
            border: 2px solid #ffd700; 
            background: #fbf7e9; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: flex-start;
        }
        
        .btn { 
            padding: 10px 15px; 
            border-radius: 5px; 
            text-decoration: none; 
            color: #fff; 
            font-weight: bold; 
            border: none;
            cursor: pointer;
        }
        
        .btn-edit {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 12px;
            background-color: #734d26;
            color: #f8eed3;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
        }
        .btn-edit:hover { background-color: #5a3b1e; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e8dcc8; }
        
        th { background-color: #e8dcc8; color: #734d26; }
        
        .service-tag { 
            background: #e8dcc8; 
            padding: 5px 10px; 
            border-radius: 5px; 
            margin-right: 5px; 
            display: inline-block; 
            color: #734d26;
        }
        
        .review-item { border-bottom: 1px solid #e8dcc8; padding: 15px 0; }
        .review-item:last-child { border-bottom: none; }
        
        .stars { color: #ffd700; font-weight: bold; font-size: 1.1em; }
        
        .card.premium h3, .card h3, .name-section h1 { color: #734d26; }
        
        .wa-btn {
            background-color: #25d366;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85em;
            display: inline-block;
            font-weight: 500;
        }
        
        .complete-btn {
            background-color: #25d366;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 0.85em;
        }
        
        .footer { 
            background-color: #734d26; 
            color: #f8eed3; 
            text-align: center; 
            padding: 20px; 
            margin-top: 40px; 
            font-size: 14px; 
        }
    </style>
</head>
<body>

<div class="header">
    <form action="HouseFixController" method="GET" style="margin: 0;">
        <input type="hidden" name="action" value="techLogout">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">
            Logout
        </button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="nav-links">
        <!-- Spacer or additional navigation links can go here -->
    </div>
</div>
<br>

<div class="dashboard-container">

    <!-- Welcome Header -->
    <div class="headerWelcome">
        <div style="display: flex; align-items: center; gap: 20px;">
            <% 
                String photoPath = (tech != null && tech.getPhotoPath() != null && !tech.getPhotoPath().trim().isEmpty()) 
                                   ? tech.getPhotoPath() 
                                   : "uploads/technician.png";
            %>
            <img src="<%= photoPath %>" 
                 alt="<%= (tech != null) ? tech.getTechName() : "Technician" %>" 
                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #734d26;">
            
            <div class="name-section">
                <h1 style="margin: 0;">Welcome, <%= (tech != null) ? tech.getTechName() : "Technician" %></h1>
                <p style="margin: 5px 0 0 0; color: #734d26; font-weight: bold;">
                    📍 Area of Operation: <%= locationDisplay %>
                </p>
                <a href="HouseFixController?action=editProfile" class="btn-edit">Edit Profile</a>
            </div>
        </div>
        <div>
            Status: <strong style="color: <%= isAvailable ? "red" : "green" %>;">
                <%= (tech != null) ? tech.getTechAvailability() : "Unavailable" %>
            </strong>
            <form action="HouseFixController" method="POST" style="display:inline; margin-left: 10px;">
                <input type="hidden" name="action" value="toggleAvailability">
                <button type="submit" onclick="return confirm('Are you sure you want to change your status?');" class="btn" style="background: #734d26;">
                    Toggle
                </button>
            </form>
        </div>
    </div>

    <!-- Usage & Plan -->
    <div style="display: flex; gap: 20px;">
        <div class="card" style="flex: 1; border-left: 5px solid <%= limitReached ? "#dc3545" : "#28a745" %>;">
            <h3>Monthly Job Usage</h3>
            <p>You have used <strong><%= monthJobs %> / <%= isBasic ? jobLimit : "Unlimited" %></strong> jobs this month.</p>
            <% if (limitReached) { %>
                <p style="color: #dc3545; font-weight: bold;">⚠️ Basic plan limit reached. Upgrade to continue.</p>
            <% } %>
        </div>

        <div class="card" style="flex: 1;">
            <h3>Subscription Plan</h3>
            <p><%= (tech != null && tech.getPlanType() != null) ? tech.getPlanType().toUpperCase() : "BASIC" %> PLAN 
            <% if (isPremium) { %> <span style="color: #ffd700;">⭐ Pro Verified</span> <% } %></p>
        </div>
    </div>

    <!-- Specialties -->
    <div class="card">
        <h3>My Specialties</h3>
        <% if (servicesList != null && !servicesList.isEmpty()) { 
            for (ServiceBean s : servicesList) { %>
                <span class="service-tag"><%= s.getServicesName() %></span>
        <%  } 
           } else { %>
            <p style="color: #777;">No categories specified.</p>
        <% } %>
        
        <% if (isPremium) { %>
            <br><br><a href="HouseFixController?action=manageServices" style="font-size: 0.8em; color: #734d26; text-decoration: none;">+ Manage Categories</a>
        <% } %>
    </div>

    <!-- Premium Card -->
    <% if (isPremium) { %>
        <div class="card premium">
            <h3>👑 Premium Pro Tools</h3>
            <a href="HouseFixController?action=advancedAnalytics" class="btn" style="background: #ffd700; color: #734d26;">View Advanced Analytics</a>
        </div>
    <% } else { %>
        <div class="card premium" style="border: 1px solid #734d26;">
            <h3>🚀 Upgrade to Premium</h3>
            <p>Unlock unlimited monthly job allocations, category modifications, and detailed customer insights.</p>
            <a href="HouseFixController?action=upgradePremium" class="btn" style="background: #734d26;">Upgrade Now</a>
        </div>
    <% } %>

    <!-- Active Assigned Jobs Section -->
    <div class="card">
        <h3>Active Assigned Jobs</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Customer</th>
                    <th style="width: 35%;">Address</th>
                    <th style="width: 15%;">Phone Number</th>
                    <th style="width: 15%;">Date & Time</th>
                    <th style="width: 15%;">Action</th>
                </tr>
            </thead>
            <tbody>
            <% 
            if (assignedJobsList != null && !assignedJobsList.isEmpty()) {
                for (BookingBean job : assignedJobsList) {
                    String cleanPhone = (job.getCustomerPhonenum() != null) ? job.getCustomerPhonenum().replaceAll("[^0-9]", "") : "";
                    String waMsg = "Hello " + (job.getCustomerName() != null ? job.getCustomerName() : "Customer") + ", I am your technician from HouseFix regarding your booking.";
                    String waLink = "https://wa.me/" + cleanPhone + "?text=" + java.net.URLEncoder.encode(waMsg, "UTF-8");
            %>
                    <tr>
                        <td><strong><%= (job.getCustomerName() != null) ? job.getCustomerName() : "Customer #" + job.getCustomerId() %></strong></td>
                        <td><%= (job.getCustomerAddress() != null) ? job.getCustomerAddress() : "N/A" %></td>
                        <td>
                            <% if (job.getCustomerPhonenum() != null && !job.getCustomerPhonenum().isEmpty()) { %>
                                <a href="<%= waLink %>" target="_blank" class="wa-btn">
                                    💬 <%= job.getCustomerPhonenum() %>
                                </a>
                            <% } else { %>
                                N/A
                            <% } %>
                        </td>
                        <td>
                            <%= job.getBookingDate() %><br>
                            <small style="color: #666;"><%= job.getBookingTime() %></small>
                        </td>
                        <td>
                        <% if (limitReached) { %>
                            <span style="color: gray;">Locked</span>
                        <% } else { %>
                            <form action="HouseFixController" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="completeJob">
                                <input type="hidden" name="bookingId" value="<%= job.getBookingId() %>">
                                <button type="submit" onclick="return confirm('Mark as completed?');" class="complete-btn">
                                    Mark as Completed
                                </button>
                            </form>
                        <% } %>
                        </td>
                    </tr>
            <% 
                }
            } else { 
            %>
                <tr><td colspan="5">No active jobs.</td></tr>
            <% } %>
            </tbody>
        </table>
    </div>

    <!-- Customer Reviews & Feedback Section -->
    <div class="card">
        <h3>Customer Reviews & Feedback</h3>
        <% if (reviewsList != null && !reviewsList.isEmpty()) { 
            for (ReviewBean review : reviewsList) { 
                int score = review.getRatingScore();
                StringBuilder stars = new StringBuilder();
                for (int i = 0; i < score; i++) stars.append("★");
                for (int i = score; i < 5; i++) stars.append("☆");
                
                String displayName = (review.getCustomerName() != null && !review.getCustomerName().trim().isEmpty()) 
                                     ? review.getCustomerName() 
                                     : "Customer (Booking #" + review.getBookingId() + ")";
        %>
                <div class="review-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #734d26; font-size: 1.05em;"><%= displayName %></strong>
                        <span class="stars"><%= stars.toString() %> (<%= score %>/5)</span>
                    </div>
                    <small style="color: #888;">
                        <%= (review.getReviewDate() != null) ? review.getReviewDate().toString().substring(0, Math.min(10, review.getReviewDate().toString().length())) : "Recent" %>
                    </small>
                    
                    <div style="margin-top: 6px;">
                        <p style="margin: 0; color: #555; font-style: italic;">
                            "<%= (review.getReviewComment() != null && !review.getReviewComment().trim().isEmpty()) ? review.getReviewComment() : "No comment left." %>"
                        </p>
                    </div>
                </div>
        <%  } 
           } else { %>
            <p style="color: #777;">No reviews received yet.</p>
        <% } %>
    </div>

</div>

<footer class="footer">
    &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
</footer>

</body>
</html>