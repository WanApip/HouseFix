package housefix;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class AreaDAO {

    public List<AreaBean> getAllAreas() throws Exception {
        List<AreaBean> areas = new ArrayList<>();
        String sql = "SELECT area_id, postcode, town, state FROM AREA ORDER BY postcode ASC";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                AreaBean ab = new AreaBean();
                ab.setAreaId(rs.getInt("area_id"));
                ab.setPostcode(rs.getString("postcode"));
                ab.setTown(rs.getString("town"));
                ab.setState(rs.getString("state"));
                areas.add(ab);
            }
        }
        return areas;
    }

    /**
     * Retrieves an AreaBean by its areaId.
     */
    public AreaBean getAreaById(int areaId) throws Exception {
        AreaBean area = null;
        String sql = "SELECT area_id, postcode, town, state FROM AREA WHERE area_id = ?";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setInt(1, areaId);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    area = new AreaBean();
                    area.setAreaId(rs.getInt("area_id"));
                    area.setPostcode(rs.getString("postcode"));
                    area.setTown(rs.getString("town"));
                    area.setState(rs.getString("state"));
                }
            }
        }
        return area;
    }
    
 // Add this method inside your existing AreaDAO class
    public boolean addArea(AreaBean area) throws Exception {
        String sql = "INSERT INTO AREA (postcode, town, state) VALUES (?, ?, ?)";

        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, area.getPostcode());
            stmt.setString(2, area.getTown());
            stmt.setString(3, area.getState());

            return stmt.executeUpdate() > 0;
        }
    }
    
 // Check if postcode already exists in the database
    public boolean existsPostcode(String postcode) throws Exception {
        String sql = "SELECT COUNT(*) FROM AREA WHERE LOWER(postcode) = LOWER(?)";
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, postcode.trim());

            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return rs.getInt(1) > 0;
                }
            }
        }
        return false;
    }
}