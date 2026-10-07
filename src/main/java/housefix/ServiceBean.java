package housefix;

public class ServiceBean {
    private int servicesId;
    private String servicesName;
    private String servicesDescription;
    private double servicesFee;

    public ServiceBean() {}

    public int getServicesId() { return servicesId; }
    public void setServicesId(int servicesId) { this.servicesId = servicesId; }

    public String getServicesName() { return servicesName; }
    public void setServicesName(String servicesName) { this.servicesName = servicesName; }

    public String getServicesDescription() { return servicesDescription; }
    public void setServicesDescription(String servicesDescription) { this.servicesDescription = servicesDescription; }

    public double getServicesFee() { return servicesFee; }
    public void setServicesFee(double servicesFee) { this.servicesFee = servicesFee; }
}
