package housefix;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DBConnection {
    
    // ⚠️ Change these values to match your actual database setup
    private static final String URL = "jdbc:mysql://localhost:3306/housefix"; 
    private static final String USER = "root"; 
    private static final String PASSWORD = "root"; 
    private static final String DRIVER = "com.mysql.jdbc.Driver";

    /**
     * Establishes and returns a connection to the database.
     */
    public static Connection getConnection() throws SQLException {
        try {
            // Load the MySQL JDBC Driver
            Class.forName(DRIVER);
            return DriverManager.getConnection(URL, USER, PASSWORD);
        } catch (ClassNotFoundException e) {
            throw new SQLException("MySQL JDBC Driver missing! Check your library dependencies.", e);
        }
    }
}
