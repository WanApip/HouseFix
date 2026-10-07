package housefix;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.LinkedHashMap;
import java.util.Map;

public class StatisticsDAO {

    // 1. Calculate Premium Subscription Revenue
    public double getPremiumRevenue() {
        double revenue = 0.0;
        String sql = "SELECT COUNT(*) AS count FROM TECHNICIAN WHERE plan_type = 'Premium'";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql);
             ResultSet rs = pstmt.executeQuery()) {
            
            if (rs.next()) {
                int count = rs.getInt("count");
                revenue = count * 99.00;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return revenue;
    }

    // 2. Calculate Web Usage Revenue
    public double getWebUsageRevenue() {
        double revenue = 0.0;
        String sql = "SELECT COUNT(*) AS count FROM BOOKING";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql);
             ResultSet rs = pstmt.executeQuery()) {
            
            if (rs.next()) {
                int count = rs.getInt("count");
                revenue = count * 1.00;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return revenue;
    }

    // 3. Get Service Demand Breakdown (ServiceName -> Count)
    public Map<String, Integer> getServiceDemand() {
        Map<String, Integer> demandMap = new LinkedHashMap<>();
        String sql = "SELECT S.services_name, COUNT(BD.booking_id) AS demand " +
                     "FROM BOOKING_DETAIL BD " +
                     "JOIN SERVICES S ON BD.services_id = S.services_id " +
                     "GROUP BY S.services_id, S.services_name";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql);
             ResultSet rs = pstmt.executeQuery()) {
            
            while (rs.next()) {
                demandMap.put(rs.getString("services_name"), rs.getInt("demand"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return demandMap;
    }
}