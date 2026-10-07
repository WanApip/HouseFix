package housefix;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

public class BookingDAO {

    // Check for double booking
    public boolean isDoubleBooked(int techId, String bookingDate, String bookingTime) throws SQLException {
        String sql = "SELECT COUNT(*) as count FROM BOOKING WHERE technician_id = ? AND booking_date = ? AND booking_time = ? AND booking_status != 'Cancelled'";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            
            ps.setInt(1, techId);
            ps.setString(2, bookingDate);
            ps.setString(3, bookingTime);
            
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return rs.getInt("count") > 0;
                }
            }
        }
        return false;
    }

    // Insert customer and return generated customer ID
    public int createCustomer(String name, String address, String phone, String email) throws SQLException {
        String sql = "INSERT INTO CUSTOMERS (customer_name, customer_address, customer_phonenum, customer_email) VALUES (?, ?, ?, ?)";
        int customerId = 0;

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            ps.setString(1, name);
            ps.setString(2, address);
            ps.setString(3, phone);
            ps.setString(4, email);
            ps.executeUpdate();

            try (ResultSet rs = ps.getGeneratedKeys()) {
                if (rs.next()) {
                    customerId = rs.getInt(1);
                }
            }
        }
        return customerId;
    }

    // Insert booking record and return generated booking ID
    public int createBooking(String date, String time, int customerId, int techId) throws SQLException {
        String sql = "INSERT INTO BOOKING (booking_date, booking_time, booking_status, customer_id, technician_id) VALUES (?, ?, 'Pending', ?, ?)";
        int bookingId = 0;

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            ps.setString(1, date);
            ps.setString(2, time);
            ps.setInt(3, customerId);
            ps.setInt(4, techId);
            ps.executeUpdate();

            try (ResultSet rs = ps.getGeneratedKeys()) {
                if (rs.next()) {
                    bookingId = rs.getInt(1);
                }
            }
        }
        return bookingId;
    }

    // Insert booking detail linkage
    public void createBookingDetail(int bookingId, int serviceId) throws SQLException {
        String sql = "INSERT INTO BOOKING_DETAIL (booking_id, services_id) VALUES (?, ?)";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            
            ps.setInt(1, bookingId);
            ps.setInt(2, serviceId);
            ps.executeUpdate();
        }
    }
    
    // Fetch booking details along with customer contact info
    public BookingBean getBookingById(int bookingId) throws SQLException {
        BookingBean booking = null;
        String sql = "SELECT b.*, c.customer_name, c.customer_phonenum, c.customer_address " +
                     "FROM BOOKING b " +
                     "LEFT JOIN CUSTOMERS c ON b.customer_id = c.customer_id " +
                     "WHERE b.booking_id = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setInt(1, bookingId);

            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    booking = new BookingBean();
                    booking.setBookingId(rs.getInt("booking_id"));
                    booking.setBookingDate(rs.getDate("booking_date"));
                    booking.setBookingTime(rs.getTime("booking_time"));
                    booking.setBookingStatus(rs.getString("booking_status"));
                    booking.setCustomerId(rs.getInt("customer_id"));
                    booking.setTechnicianId(rs.getInt("technician_id"));
                    
                    // Map customer details from JOIN
                    booking.setCustomerName(rs.getString("customer_name"));
                    booking.setCustomerAddress(rs.getString("customer_address"));
                    booking.setCustomerPhonenum(rs.getString("customer_phonenum"));
                }
            }
        }
        return booking;
    }

    // ==========================================
    // NEW METHODS FOR TECHNICIAN DASHBOARD
    // ==========================================

    // Fetch active/pending jobs assigned to a specific technician
    public List<BookingBean> getPendingJobsByTechId(int techId) throws SQLException {
        List<BookingBean> list = new ArrayList<>();
        String sql = "SELECT b.*, c.customer_name, c.customer_phonenum, c.customer_address " +
                     "FROM BOOKING b " +
                     "JOIN CUSTOMERS c ON b.customer_id = c.customer_id " +
                     "WHERE b.technician_id = ? AND b.booking_status IN ('Pending', 'Assigned', 'In Progress') " +
                     "ORDER BY b.booking_date ASC, b.booking_time ASC";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setInt(1, techId);

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    BookingBean job = new BookingBean();
                    job.setBookingId(rs.getInt("booking_id"));
                    job.setBookingDate(rs.getDate("booking_date"));
                    job.setBookingTime(rs.getTime("booking_time"));
                    job.setBookingStatus(rs.getString("booking_status"));
                    job.setCustomerId(rs.getInt("customer_id"));
                    job.setTechnicianId(rs.getInt("technician_id"));
                  
                    // Map customer details from JOIN
                    job.setCustomerName(rs.getString("customer_name"));
                    job.setCustomerAddress(rs.getString("customer_address"));
                    job.setCustomerPhonenum(rs.getString("customer_phonenum"));

                    list.add(job);
                }
            }
        }
        return list;
    }

    // Update job status (e.g., set status to 'Completed')
    public boolean updateBookingStatus(int bookingId, String status) throws SQLException {
        String sql = "UPDATE BOOKING SET booking_status = ? WHERE booking_id = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setString(1, status);
            ps.setInt(2, bookingId);

            return ps.executeUpdate() > 0;
        }
    }
    
    public double getTotalRevenueByTechId(int techId) {
        double totalRevenue = 0.0;
        String sql = "SELECT SUM(S.services_fee) AS rev " +
                     "FROM BOOKING B " +
                     "JOIN booking_detail BD ON B.booking_id = BD.booking_id " +
                     "JOIN SERVICES S ON BD.services_id = S.services_id " +
                     "WHERE B.technician_id = ? AND B.booking_status = 'Completed'";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {

            pstmt.setInt(1, techId);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    totalRevenue = rs.getDouble("rev");
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return totalRevenue;
    }

    public List<CategoryAnalyticsBean> getCategoryAnalyticsByTechId(int techId) {
        List<CategoryAnalyticsBean> list = new ArrayList<>();
        String sql = "SELECT S.services_name, COUNT(B.booking_id) AS total_jobs, SUM(S.services_fee) AS total_inc " +
                     "FROM BOOKING B " +
                     "JOIN booking_detail BD ON B.booking_id = BD.booking_id " +
                     "JOIN SERVICES S ON BD.services_id = S.services_id " +
                     "WHERE B.technician_id = ? AND B.booking_status = 'Completed' " +
                     "GROUP BY S.services_id, S.services_name";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {

            pstmt.setInt(1, techId);
            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next()) {
                    CategoryAnalyticsBean bean = new CategoryAnalyticsBean(
                        rs.getString("services_name"),
                        rs.getInt("total_jobs"),
                        rs.getDouble("total_inc")
                    );
                    list.add(bean);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return list;
    }
    
    public List<BookingBean> getAllBookingsWithDetails() throws Exception {
        List<BookingBean> list = new ArrayList<>();
        String sql = "SELECT B.*, C.customer_name, C.customer_phonenum, C.customer_email, " +
                     "       GROUP_CONCAT(S.services_name SEPARATOR ', ') AS services_list " +
                     "FROM BOOKING B " +
                     "JOIN CUSTOMERS C ON B.customer_id = C.customer_id " +
                     "LEFT JOIN BOOKING_DETAIL BD ON B.booking_id = BD.booking_id " +
                     "LEFT JOIN SERVICES S ON BD.services_id = S.services_id " +
                     "GROUP BY B.booking_id " +
                     "ORDER BY B.booking_date DESC";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                BookingBean b = new BookingBean();
                b.setBookingId(rs.getInt("booking_id"));
                b.setBookingDate(rs.getDate("booking_date"));
                b.setBookingStatus(rs.getString("booking_status"));
                b.setCustomerId(rs.getInt("customer_id"));
                b.setCustomerName(rs.getString("customer_name"));
                b.setCustomerPhonenum(rs.getString("customer_phonenum"));
                b.setCustomerEmail(rs.getString("customer_email"));
                b.setServicesList(rs.getString("services_list"));
                list.add(b);
            }
        }
        return list;
    }
}