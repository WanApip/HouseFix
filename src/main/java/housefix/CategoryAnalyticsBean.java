package housefix;

public class CategoryAnalyticsBean {
    private String serviceName;
    private int totalJobs;
    private double totalIncome;

    public CategoryAnalyticsBean() {}

    public CategoryAnalyticsBean(String serviceName, int totalJobs, double totalIncome) {
        this.serviceName = serviceName;
        this.totalJobs = totalJobs;
        this.totalIncome = totalIncome;
    }

    public String getServiceName() { return serviceName; }
    public void setServiceName(String serviceName) { this.serviceName = serviceName; }

    public int getTotalJobs() { return totalJobs; }
    public void setTotalJobs(int totalJobs) { this.totalJobs = totalJobs; }

    public double getTotalIncome() { return totalIncome; }
    public void setTotalIncome(double totalIncome) { this.totalIncome = totalIncome; }
}