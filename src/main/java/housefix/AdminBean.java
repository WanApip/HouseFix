package housefix;

public class AdminBean {
    private int adminId;
    private String usernameAdmin;
    private String passwordHash;

    public AdminBean() {}

    public int getAdminId() { return adminId; }
    public void setAdminId(int adminId) { this.adminId = adminId; }

    public String getUsernameAdmin() { return usernameAdmin; }
    public void setUsernameAdmin(String usernameAdmin) { this.usernameAdmin = usernameAdmin; }

    public String getPasswordHash() { return passwordHash; }
    public void setPasswordHash(String passwordHash) { this.passwordHash = passwordHash; }
}