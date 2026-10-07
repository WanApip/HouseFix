package housefix;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.HashMap;
import java.util.Map;

public class AdminDAO {

    public AdminBean authenticateAdmin(String username, String password) {
        String sql = "SELECT * FROM ADMIN WHERE username_admin = ?";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {

            pstmt.setString(1, username);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    String storedPassword = rs.getString("password_hash");
                    // Direct string comparison matching your legacy PHP login logic
                    if (password != null && password.equals(storedPassword)) {
                        AdminBean admin = new AdminBean();
                        admin.setAdminId(rs.getInt("admin_id"));
                        admin.setUsernameAdmin(rs.getString("username_admin"));
                        admin.setPasswordHash(storedPassword);
                        return admin;
                    }
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null; // Authentication failed
    }
    
    public Map<String, Integer> getDashboardStats() {
        Map<String, Integer> stats = new HashMap<>();
        stats.put("totalBookings", 0);
        stats.put("totalTechnicians", 0);
        stats.put("pendingTechs", 0);
        stats.put("totalCustomers", 0);

        String sqlBookings = "SELECT COUNT(*) as count FROM BOOKING";
        String sqlTechs = "SELECT COUNT(*) as count FROM TECHNICIAN";
        String sqlPendingTechs = "SELECT COUNT(*) as count FROM TECHNICIAN WHERE tech_status = 'Pending'";
        String sqlCustomers = "SELECT COUNT(*) as count FROM CUSTOMERS";

        try (Connection conn = DBConnection.getConnection()) {
            
            // Total Bookings
            try (PreparedStatement pstmt = conn.prepareStatement(sqlBookings);
                 ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) stats.put("totalBookings", rs.getInt("count"));
            }

            // Total Technicians
            try (PreparedStatement pstmt = conn.prepareStatement(sqlTechs);
                 ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) stats.put("totalTechnicians", rs.getInt("count"));
            }

            // Pending Technicians
            try (PreparedStatement pstmt = conn.prepareStatement(sqlPendingTechs);
                 ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) stats.put("pendingTechs", rs.getInt("count"));
            }

            // Total Customers
            try (PreparedStatement pstmt = conn.prepareStatement(sqlCustomers);
                 ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) stats.put("totalCustomers", rs.getInt("count"));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return stats;
    }
}