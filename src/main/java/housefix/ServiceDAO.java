package housefix;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class ServiceDAO {

    public List<ServiceBean> getAllServices() throws Exception {
        List<ServiceBean> services = new ArrayList<>();
          String sql = "SELECT services_id, services_name, services_description, services_fee FROM SERVICES";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                ServiceBean sb = new ServiceBean();
                sb.setServicesId(rs.getInt("services_id"));
                sb.setServicesName(rs.getString("services_name"));
                sb.setServicesDescription(rs.getString("services_description"));
                sb.setServicesFee(rs.getDouble("services_fee"));
                services.add(sb);
            }
        }
        return services;
    }
    
    public ServiceBean getServiceById(int serviceId) throws Exception {
        ServiceBean service = null;
        String sql = "SELECT * FROM SERVICES WHERE services_id = ?";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            
            ps.setInt(1, serviceId);
            
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    service = new ServiceBean();
                    service.setServicesId(rs.getInt("services_id"));
                    service.setServicesName(rs.getString("services_name"));
                    service.setServicesFee(rs.getDouble("services_fee"));
                    // Map any other service attributes if present in your bean
                }
            }
        }
        return service;
    }
    
    public List<ServiceBean> getServiceByTechId(int techId) {
        List<ServiceBean> list = new ArrayList<>();
        
        // Join SERVICES with TECHNICIAN_SERVICES junction table
        String sql = "SELECT s.* FROM services s " +
                     "JOIN technician_services ts ON s.services_id = ts.services_id " +
                     "WHERE ts.technician_id = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setInt(1, techId);

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    ServiceBean service = new ServiceBean();
                    service.setServicesId(rs.getInt("services_id"));
                    service.setServicesName(rs.getString("services_name"));
                    service.setServicesDescription(rs.getString("services_description"));
                    service.setServicesFee(rs.getDouble("services_fee"));
                    list.add(service);
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        
        return list;
    }
    
    public void updateTechnicianServices(int techId, String[] serviceIds) throws Exception {
        String deleteSql = "DELETE FROM TECHNICIAN_SERVICES WHERE technician_id = ?";
        String insertSql = "INSERT INTO TECHNICIAN_SERVICES (technician_id, services_id) VALUES (?, ?)";

        try (Connection conn = DBConnection.getConnection()) {
            conn.setAutoCommit(false); // Enable transaction block

            // 1. Delete existing connections
            try (PreparedStatement deleteStmt = conn.prepareStatement(deleteSql)) {
                deleteStmt.setInt(1, techId);
                deleteStmt.executeUpdate();
            }

            // 2. Insert selected categories
            if (serviceIds != null && serviceIds.length > 0) {
                try (PreparedStatement insertStmt = conn.prepareStatement(insertSql)) {
                    for (String serviceIdStr : serviceIds) {
                        int serviceId = Integer.parseInt(serviceIdStr);
                        insertStmt.setInt(1, techId);
                        insertStmt.setInt(2, serviceId);
                        insertStmt.addBatch();
                    }
                    insertStmt.executeBatch();
                }
            }

            conn.commit(); // Commit transaction
        } catch (Exception e) {
            e.printStackTrace();
            throw e;
        }
    }
    
 // 1. Check if a service name already exists (case-insensitive duplicate check)
    public boolean existsServiceName(String servicesName) throws Exception {
        String sql = "SELECT COUNT(*) FROM SERVICES WHERE LOWER(services_name) = LOWER(?)";
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, servicesName.trim());

            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return rs.getInt(1) > 0;
                }
            }
        }
        return false;
    }

    // 2. Add a new service
    public boolean addService(ServiceBean service) throws Exception {
        String sql = "INSERT INTO SERVICES (services_name, services_description, services_fee) VALUES (?, ?, ?)";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, service.getServicesName());
            stmt.setString(2, service.getServicesDescription());
            stmt.setDouble(3, service.getServicesFee());

            return stmt.executeUpdate() > 0;
        }
    }

    // 3. Update fee for an existing service
    public boolean updateServiceFee(int servicesId, double newFee) throws Exception {
        String sql = "UPDATE SERVICES SET services_fee = ? WHERE services_id = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setDouble(1, newFee);
            stmt.setInt(2, servicesId);

            return stmt.executeUpdate() > 0;
        }
    }
}