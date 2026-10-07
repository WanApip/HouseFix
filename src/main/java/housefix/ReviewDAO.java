package housefix;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class ReviewDAO {

    /**
     * Retrieves all reviews submitted for a specific technician by joining REVIEW, BOOKING, and CUSTOMERS.
     */
    public List<ReviewBean> getReviewsByTechId(int techId) {
        List<ReviewBean> list = new ArrayList<>();
        
        // Joined CUSTOMERS table through BOOKING to fetch customer_name
        String sql = "SELECT r.*, c.customer_name " +
                     "FROM REVIEWS r " +
                     "JOIN BOOKING b ON r.booking_id = b.booking_id " +
                     "JOIN CUSTOMERS c ON b.customer_id = c.customer_id " +
                     "WHERE b.technician_id = ? " +
                     "ORDER BY r.review_date DESC";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setInt(1, techId);

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    ReviewBean review = new ReviewBean();
                    
                    review.setReviewId(rs.getInt("review_id"));
                    review.setRatingScore(rs.getInt("rating_score"));
                    review.setReviewComment(rs.getString("review_comment"));
                    review.setBookingId(rs.getInt("booking_id"));
                    review.setReviewDate(rs.getTimestamp("review_date"));

                    // SET THE CUSTOMER NAME HERE
                    review.setCustomerName(rs.getString("customer_name"));

                    list.add(review);
                }
            }
        } catch (SQLException e) {
            System.err.println("Error in getReviewsByTechId: " + e.getMessage());
            e.printStackTrace();
        } catch (Exception e) {
            e.printStackTrace();
        }

        return list;
    }

    /**
     * Calculates the average star rating score for a technician.
     * Returns 0.0 if no reviews exist yet.
     */
    public double getAverageRatingByTechId(int techId) {
        String sql = "SELECT AVG(r.rating_score) AS avg_rating " +
                     "FROM REVIEWS r " +
                     "JOIN BOOKING b ON r.booking_id = b.booking_id " +
                     "WHERE b.technician_id = ?";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setInt(1, techId);

            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return rs.getDouble("avg_rating");
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

        return 0.0;
    }
    
    /**
     * Inserts a new customer review into the REVIEWS table.
     */
    public boolean addReview(ReviewBean review) {
        String sql = "INSERT INTO REVIEWS (booking_id, rating_score, review_comment) VALUES (?, ?, ?)";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {

            ps.setInt(1, review.getBookingId());
            ps.setInt(2, review.getRatingScore());
            ps.setString(3, review.getReviewComment());

            return ps.executeUpdate() > 0;

        } catch (SQLException e) {
            System.err.println("Error in addReview: " + e.getMessage());
            e.printStackTrace();
        } catch (Exception e) {
            e.printStackTrace();
        }

        return false;
    }
}