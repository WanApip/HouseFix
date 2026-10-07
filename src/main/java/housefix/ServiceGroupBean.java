package housefix;

import java.util.ArrayList;
import java.util.List;

public class ServiceGroupBean {
    private int serviceId;
    private String serviceName;
    private List<TechnicianBean> technicians;

    public ServiceGroupBean() {
        this.technicians = new ArrayList<>();
    }

    public ServiceGroupBean(int serviceId, String serviceName) {
        this.serviceId = serviceId;
        this.serviceName = serviceName;
        this.technicians = new ArrayList<>();
    }

    public int getServiceId() { return serviceId; }
    public void setServiceId(int serviceId) { this.serviceId = serviceId; }

    public String getServiceName() { return serviceName; }
    public void setServiceName(String serviceName) { this.serviceName = serviceName; }

    public List<TechnicianBean> getTechnicians() { return technicians; }
    public void setTechnicians(List<TechnicianBean> technicians) { this.technicians = technicians; }

    public void addTechnician(TechnicianBean tech) {
        this.technicians.add(tech);
    }
}