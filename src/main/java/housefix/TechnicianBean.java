package housefix;

public class TechnicianBean {

    // Database Fields 
    private int technicianId;
    private String techName;
    private String techPhonenum;
    private String techEmail;
    private Integer areaId;          
    private String ssmNum;
    private String photoPath;
    private String techAvailability;
    private String passwordHash;
    private String planType;        // 'Basic', 'Premium'
    private String paymentStatus;   // 'Unpaid', 'Paid'
    private String techStatus;      // 'Pending', 'Approved', 'Rejected'

    // Non-database field for display (JOIN query)
    private String servicesList;

    // Default Constructor
    public TechnicianBean() {}

    // Overloaded Constructor
    public TechnicianBean(int technicianId, String techName, String techPhonenum, String techEmail, 
                          Integer areaId, String ssmNum, String photoPath, String techAvailability, 
                          String passwordHash, String planType, String paymentStatus, String techStatus) {
        this.technicianId = technicianId;
        this.techName = techName;
        this.techPhonenum = techPhonenum;
        this.techEmail = techEmail;
        this.areaId = areaId;
        this.ssmNum = ssmNum;
        this.photoPath = photoPath;
        this.techAvailability = techAvailability;
        this.passwordHash = passwordHash;
        this.planType = planType;
        this.paymentStatus = paymentStatus;
        this.techStatus = techStatus;
    }

    // Getters and Setters
    public int getTechnicianId() { return technicianId; }
    public void setTechnicianId(int technicianId) { this.technicianId = technicianId; }

    public String getTechName() { return techName; }
    public void setTechName(String techName) { this.techName = techName; }

    public String getTechPhonenum() { return techPhonenum; }
    public void setTechPhonenum(String techPhonenum) { this.techPhonenum = techPhonenum; }

    public String getTechEmail() { return techEmail; }
    public void setTechEmail(String techEmail) { this.techEmail = techEmail; }

    public Integer getAreaId() { return areaId; }
    public void setAreaId(Integer areaId) { this.areaId = areaId; }

    public String getSsmNum() { return ssmNum; }
    public void setSsmNum(String ssmNum) { this.ssmNum = ssmNum; }

    public String getPhotoPath() { return photoPath; }
    public void setPhotoPath(String photoPath) { this.photoPath = photoPath; }

    public String getTechAvailability() { return techAvailability; }
    public void setTechAvailability(String techAvailability) { this.techAvailability = techAvailability; }

    public String getPasswordHash() { return passwordHash; }
    public void setPasswordHash(String passwordHash) { this.passwordHash = passwordHash; }

    public String getPlanType() { return planType; }
    public void setPlanType(String planType) { this.planType = planType; }

    public String getPaymentStatus() { return paymentStatus; }
    public void setPaymentStatus(String paymentStatus) { this.paymentStatus = paymentStatus; }

    public String getTechStatus() { return techStatus; }
    public void setTechStatus(String techStatus) { this.techStatus = techStatus; }

    public String getServicesList() { return servicesList; }
    public void setServicesList(String servicesList) { this.servicesList = servicesList; }
}