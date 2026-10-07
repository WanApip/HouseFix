package housefix;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.Statement;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class TechnicianDAO {

    public List<TechnicianBean> getAvailableTechnicians(int areaId, int serviceId) {
        List<TechnicianBean> availableList = new ArrayList<>();

        String sql = "SELECT t.* FROM technician t " +
                     "JOIN technician_services ts ON t.technician_id = ts.technician_id " +
                     "WHERE t.area_id = ? AND ts.services_id = ? " +
                     "AND t.tech_availability = 'Available' AND t.tech_status = 'Approved'";

        try (Connection con = DBConnection.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {

            ps.setInt(1, areaId);
            ps.setInt(2, serviceId);

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    int techId = rs.getInt("technician_id");
                    String planType = rs.getString("plan_type");

                    // Enforce basic user limit rule (max 10 jobs/month)
                    if ("Basic".equalsIgnoreCase(planType)) {
                        int currentMonthJobs = getMonthlyJobCount(con, techId);
                        if (currentMonthJobs >= 10) {
                            continue; // Skip technicians who reached their monthly quota
                        }
                    }

                    TechnicianBean bean = new TechnicianBean();
                    bean.setTechnicianId(techId);
                    bean.setTechName(rs.getString("tech_name"));
                    bean.setTechPhonenum(rs.getString("tech_phonenum"));
                    bean.setTechEmail(rs.getString("tech_email"));
                    bean.setAreaId(rs.getInt("area_id"));
                    bean.setSsmNum(rs.getString("ssm_num"));
                    bean.setPhotoPath(rs.getString("photo_path"));
                    bean.setTechAvailability(rs.getString("tech_availability"));
                    bean.setPasswordHash(rs.getString("password_hash"));
                    bean.setPlanType(planType);
                    bean.setPaymentStatus(rs.getString("payment_status"));
                    bean.setTechStatus(rs.getString("tech_status"));

                    availableList.add(bean);
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        return availableList;
    }

    /**
     * Public overload method to retrieve monthly job count for controller calls.
     */
    public int getMonthlyJobCount(int techId) {
        try (Connection con = DBConnection.getConnection()) {
            return getMonthlyJobCount(con, techId);
        } catch (Exception e) {
            e.printStackTrace();
            return 0;
        }
    }

    private int getMonthlyJobCount(Connection con, int techId) {
        int count = 0;
        String sql = "SELECT COUNT(*) FROM booking " +
                     "WHERE technician_id = ? " +
                     "AND MONTH(booking_date) = MONTH(CURRENT_DATE()) " +
                     "AND YEAR(booking_date) = YEAR(CURRENT_DATE())";

        try (PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setInt(1, techId);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    count = rs.getInt(1);
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return count;
    }
    
    public TechnicianBean getTechnicianById(int techId) throws Exception {
        TechnicianBean tech = null;
        String sql = "SELECT * FROM TECHNICIAN WHERE technician_id = ?";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            
            ps.setInt(1, techId);
            
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    tech = new TechnicianBean();
                    tech.setTechnicianId(rs.getInt("technician_id"));
                    tech.setTechName(rs.getString("tech_name"));
                    tech.setTechPhonenum(rs.getString("tech_phonenum"));
                    tech.setTechEmail(rs.getString("tech_email"));
                    tech.setAreaId(rs.getInt("area_id"));
                    tech.setSsmNum(rs.getString("ssm_num"));
                    tech.setPhotoPath(rs.getString("photo_path"));
                    tech.setTechAvailability(rs.getString("tech_availability"));
                    tech.setPasswordHash(rs.getString("password_hash"));
                    tech.setPlanType(rs.getString("plan_type"));
                    tech.setPaymentStatus(rs.getString("payment_status"));
                    tech.setTechStatus(rs.getString("tech_status"));
                }
            }
        }
        return tech;
    }

    /**
     * Registers a new technician and links their selected service using a database transaction.
     */
    public boolean registerTechnician(String name, String email, String password, String phone, 
                                      String ssm, int areaId, int serviceId) throws SQLException {
        
        String sqlTech = "INSERT INTO TECHNICIAN (tech_name, tech_email, password_hash, tech_phonenum, ssm_num, area_id, plan_type, tech_status, tech_availability) " +
                         "VALUES (?, ?, ?, ?, ?, ?, 'Basic', 'Pending', 'Unavailable')";
                         
        String sqlBridge = "INSERT INTO TECHNICIAN_SERVICES (technician_id, services_id) VALUES (?, ?)";

        Connection conn = null;
        PreparedStatement psTech = null;
        PreparedStatement psBridge = null;
        ResultSet rsKeys = null;

        try {
            conn = DBConnection.getConnection();
            conn.setAutoCommit(false); // Start transaction

            // 1. Insert into TECHNICIAN table
            psTech = conn.prepareStatement(sqlTech, Statement.RETURN_GENERATED_KEYS);
            psTech.setString(1, name);
            psTech.setString(2, email);
            psTech.setString(3, password);
            psTech.setString(4, phone);
            psTech.setString(5, ssm);
            psTech.setInt(6, areaId);

            int rowsAffected = psTech.executeUpdate();

            if (rowsAffected == 0) {
                conn.rollback();
                return false;
            }

            // 2. Obtain newly generated technician_id
            rsKeys = psTech.getGeneratedKeys();
            int techId = 0;
            if (rsKeys.next()) {
                techId = rsKeys.getInt(1);
            } else {
                conn.rollback();
                return false;
            }

            // 3. Insert record into TECHNICIAN_SERVICES bridge table
            psBridge = conn.prepareStatement(sqlBridge);
            psBridge.setInt(1, techId);
            psBridge.setInt(2, serviceId);
            psBridge.executeUpdate();

            conn.commit(); // Commit transaction
            return true;

        } catch (SQLException e) {
            if (conn != null) {
                conn.rollback(); // Rollback if any part fails
            }
            throw e;
        } finally {
            if (rsKeys != null) rsKeys.close();
            if (psTech != null) psTech.close();
            if (psBridge != null) psBridge.close();
            if (conn != null) {
                conn.setAutoCommit(true);
                conn.close();
            }
        }
    }
    
    public TechnicianBean getTechnicianByEmail(String email) throws Exception {
        TechnicianBean tech = null;
        String sql = "SELECT * FROM TECHNICIAN WHERE tech_email = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setString(1, email);

            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    tech = new TechnicianBean();
                    tech.setTechnicianId(rs.getInt("technician_id"));
                    tech.setTechName(rs.getString("tech_name"));
                    tech.setTechEmail(rs.getString("tech_email"));
                    tech.setTechPhonenum(rs.getString("tech_phonenum"));
                    tech.setPasswordHash(rs.getString("password_hash"));
                    tech.setTechStatus(rs.getString("tech_status"));
                    tech.setTechAvailability(rs.getString("tech_availability"));
                    tech.setPlanType(rs.getString("plan_type"));
                    tech.setPaymentStatus(rs.getString("payment_status"));
                    tech.setSsmNum(rs.getString("ssm_num"));
                    tech.setPhotoPath(rs.getString("photo_path"));
                    tech.setAreaId(rs.getInt("area_id"));
                }
            }
        }
        return tech;
    }

    /**
     * Toggles the technician's availability status between 'Available' and 'Unavailable'.
     */
    public boolean toggleAvailability(int techId) {
        String sqlSelect = "SELECT tech_availability FROM TECHNICIAN WHERE technician_id = ?";
        String sqlUpdate = "UPDATE TECHNICIAN SET tech_availability = ? WHERE technician_id = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement psSel = conn.prepareStatement(sqlSelect)) {

            psSel.setInt(1, techId);
            try (ResultSet rs = psSel.executeQuery()) {
                if (rs.next()) {
                    String currentStatus = rs.getString("tech_availability");
                    String newStatus = "Available".equalsIgnoreCase(currentStatus) ? "Unavailable" : "Available";

                    try (PreparedStatement psUpd = conn.prepareStatement(sqlUpdate)) {
                        psUpd.setString(1, newStatus);
                        psUpd.setInt(2, techId);
                        return psUpd.executeUpdate() > 0;
                    }
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return false;
    }

    /**
     * Updates technician profile details (Name, Phone Number, Photo Path)
     */
    public boolean updateTechnicianProfile(TechnicianBean tech) {
        String sql = "UPDATE TECHNICIAN SET tech_name = ?, tech_phonenum = ?, photo_path = ? WHERE technician_id = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setString(1, tech.getTechName());
            ps.setString(2, tech.getTechPhonenum());
            ps.setString(3, tech.getPhotoPath());
            ps.setInt(4, tech.getTechnicianId());

            return ps.executeUpdate() > 0;

        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }
    
    public boolean upgradeToPremium(int techId) {
        String sql = "UPDATE TECHNICIAN SET plan_type = 'Premium', payment_status = 'Paid' WHERE technician_id = ?";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            
            pstmt.setInt(1, techId);
            int rowsUpdated = pstmt.executeUpdate();
            return rowsUpdated > 0;
            
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }
    
    public List<TechnicianBean> getAllTechniciansWithServices() {
        List<TechnicianBean> list = new ArrayList<>();
        String sql = "SELECT T.*, GROUP_CONCAT(S.services_name SEPARATOR ', ') as services_list " +
                     "FROM TECHNICIAN T " +
                     "LEFT JOIN TECHNICIAN_SERVICES TS ON T.technician_id = TS.technician_id " +
                     "LEFT JOIN SERVICES S ON TS.services_id = S.services_id " +
                     "GROUP BY T.technician_id";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql);
             ResultSet rs = pstmt.executeQuery()) {

            while (rs.next()) {
                TechnicianBean tech = new TechnicianBean();
                tech.setTechnicianId(rs.getInt("technician_id"));
                tech.setTechName(rs.getString("tech_name"));
                tech.setTechPhonenum(rs.getString("tech_phonenum")); // Matches techPhonenum
                tech.setTechEmail(rs.getString("tech_email"));
                tech.setAreaId(rs.getObject("area_id") != null ? rs.getInt("area_id") : null);
                tech.setSsmNum(rs.getString("ssm_num"));
                tech.setPhotoPath(rs.getString("photo_path"));
                tech.setTechAvailability(rs.getString("tech_availability"));
                tech.setPasswordHash(rs.getString("password_hash"));
                tech.setPlanType(rs.getString("plan_type"));
                tech.setPaymentStatus(rs.getString("payment_status"));
                tech.setTechStatus(rs.getString("tech_status"));
                tech.setServicesList(rs.getString("services_list"));
                
                list.add(tech);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return list;
    }

    public boolean updateTechnicianStatus(int technicianId, String newStatus) {
        String sql = "UPDATE TECHNICIAN SET tech_status = ? WHERE technician_id = ?";
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {

            pstmt.setString(1, newStatus);
            pstmt.setInt(2, technicianId);
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }
    
    public List<ServiceGroupBean> getServicesDirectory() {
        List<ServiceGroupBean> serviceGroups = new ArrayList<>();
        
        String sql = "SELECT S.services_id, S.services_name, " +
                     "T.technician_id, T.tech_name, T.photo_path " +
                     "FROM SERVICES S " +
                     "LEFT JOIN TECHNICIAN_SERVICES TS ON S.services_id = TS.services_id " +
                     "LEFT JOIN TECHNICIAN T ON TS.technician_id = T.technician_id " +
                     "AND T.tech_status = 'Approved' " +
                     "ORDER BY S.services_name, T.tech_name";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql);
             ResultSet rs = pstmt.executeQuery()) {

            ServiceGroupBean currentGroup = null;

            while (rs.next()) {
                int serviceId = rs.getInt("services_id");
                String serviceName = rs.getString("services_name");

                // Check if we started a new service group
                if (currentGroup == null || currentGroup.getServiceId() != serviceId) {
                    currentGroup = new ServiceGroupBean(serviceId, serviceName);
                    serviceGroups.add(currentGroup);
                }

                int techId = rs.getInt("technician_id");
                if (!rs.wasNull()) {
                    TechnicianBean tech = new TechnicianBean();
                    tech.setTechnicianId(techId);
                    tech.setTechName(rs.getString("tech_name"));
                    tech.setPhotoPath(rs.getString("photo_path"));
                    currentGroup.addTechnician(tech);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return serviceGroups;
    }
}