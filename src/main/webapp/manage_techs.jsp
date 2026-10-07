<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.List" %>
<%@ page import="housefix.TechnicianBean" %>

<%
    if (session.getAttribute("admin_id") == null) {
        response.sendRedirect("HouseFixController?action=showAdminLogin");
        return;
    }

    List<TechnicianBean> pendingTechs = (List<TechnicianBean>) request.getAttribute("pendingTechs");
    List<TechnicianBean> activeTechs = (List<TechnicianBean>) request.getAttribute("activeTechs");
%>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HouseFix Admin | Manage Technicians</title>
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
        
        .empty-space { width: 80px; }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .page-title { margin-bottom: 30px; }
        .page-title h1 { font-size: 28px; color: #734d26; margin: 0; }
        .page-title p { color: #666; margin: 5px 0 0 0; }
        
        .card { 
            background: #fbf7e9; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            margin-bottom: 20px;
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #e8dcc8; color: #734d26; }
        
        .status-Approved { color: green; font-weight: bold; }
        .status-Pending { color: orange; font-weight: bold; }
        .status-Suspended, .status-Rejected { color: red; font-weight: bold; }
        
        h2 { color: #734d26; margin-top: 0; }
        
        .service-tag { 
            font-size: 0.85em; 
            background: #e8dcc8; 
            padding: 2px 6px; 
            border-radius: 4px; 
            color: #734d26;
        }
        
        .btn-accept { color: green; font-weight: bold; margin-right: 10px; text-decoration: none; }
        .btn-reject { color: red; font-weight: bold; text-decoration: none; }
        .btn-suspend { color: red; text-decoration: none; }
        .btn-reapprove { color: green; text-decoration: none; }
        .no-data { color: #999; font-style: italic; padding: 20px; text-align: center; }

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
        <a href="HouseFixController?action=adminDashboard" class="btn-back">Back</a>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div class="empty-space"></div>
    </div>

    <div class="container">
        <div class="page-title">
            <br><h1>Manage Technicians</h1>
            <p>Approve, suspend, or manage technician accounts</p>
        </div>

        <!-- Pending Registrations Card -->
        <div class="card">
            <h2>Pending Registrations</h2>
            <% if (pendingTechs != null && !pendingTechs.isEmpty()) { %>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>SSM Number</th>
                            <th>Services</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <% for (TechnicianBean row : pendingTechs) { %>
                        <tr>
                            <td><%= row.getTechName() != null ? row.getTechName() : "" %></td>
                            <td><%= row.getTechEmail() != null ? row.getTechEmail() : "" %></td>
                            <td><%= row.getTechPhonenum() != null ? row.getTechPhonenum() : "" %></td>
                            <td><%= row.getSsmNum() != null ? row.getSsmNum() : "" %></td>
                            <td>
                                <span class="service-tag">
                                    <%= (row.getServicesList() != null && !row.getServicesList().isEmpty()) ? row.getServicesList() : "None" %>
                                </span>
                            </td>
                            <td>
                                <a href="HouseFixController?action=updateTechStatus&statusAction=approve&id=<%= row.getTechnicianId() %>" 
                                   onclick="return confirm('APPROVE this technician?');" 
                                   class="btn-accept">✓ Accept</a>
                                <a href="HouseFixController?action=updateTechStatus&statusAction=reject&id=<%= row.getTechnicianId() %>" 
                                   onclick="return confirm('REJECT this technician?');" 
                                   class="btn-reject">✗ Reject</a>
                            </td>
                        </tr>
                        <% } %>
                    </tbody>
                </table>
            <% } else { %>
                <p class="no-data">No pending registrations.</p>
            <% } %>
        </div>

        <!-- Active & Suspended Technicians Card -->
        <div class="card">
            <h2>Active & Suspended Technicians</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>SSM</th>
                        <th>Services</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <% if (activeTechs != null && !activeTechs.isEmpty()) { %>
                        <% for (TechnicianBean row : activeTechs) { %>
                        <tr>
                            <td><%= row.getTechName() != null ? row.getTechName() : "" %></td>
                            <td><%= row.getTechEmail() != null ? row.getTechEmail() : "" %></td>
                            <td><%= row.getTechPhonenum() != null ? row.getTechPhonenum() : "" %></td>
                            <td><%= row.getSsmNum() != null ? row.getSsmNum() : "" %></td>
                            <td>
                                <span class="service-tag">
                                    <%= (row.getServicesList() != null && !row.getServicesList().isEmpty()) ? row.getServicesList() : "None" %>
                                </span>
                            </td>
                            <td class="status-<%= row.getTechStatus() %>"><%= row.getTechStatus() %></td>
                            <td>
                                <% if ("Approved".equalsIgnoreCase(row.getTechStatus())) { %>
                                    <a href="HouseFixController?action=updateTechStatus&statusAction=suspend&id=<%= row.getTechnicianId() %>" 
                                       onclick="return confirm('SUSPEND this technician?');" 
                                       class="btn-suspend">Suspend</a>
                                <% } else { %>
                                    <a href="HouseFixController?action=updateTechStatus&statusAction=approve&id=<%= row.getTechnicianId() %>" 
                                       onclick="return confirm('RE-APPROVE this technician?');" 
                                       class="btn-reapprove">✓ Re-Approve</a>
                                <% } %>
                            </td>
                        </tr>
                        <% } %>
                    <% } %>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>

</body>
</html>