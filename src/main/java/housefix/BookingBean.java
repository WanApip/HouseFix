package housefix;

import java.sql.Date;
import java.sql.Time;

public class BookingBean {
    private int bookingId;
    private Date bookingDate;
    private Time bookingTime;
    private String bookingStatus;
    private int customerId;
    private int technicianId;
    
    // Additional Customer & Service Details
    private String customerName;
    private String customerAddress;
    private String customerPhonenum;
    private String customerEmail;
    private String servicesList;

    public BookingBean() {}

    public int getBookingId() { return bookingId; }
    public void setBookingId(int bookingId) { this.bookingId = bookingId; }

    public Date getBookingDate() { return bookingDate; }
    public void setBookingDate(Date bookingDate) { this.bookingDate = bookingDate; }

    public Time getBookingTime() { return bookingTime; }
    public void setBookingTime(Time bookingTime) { this.bookingTime = bookingTime; }

    public String getBookingStatus() { return bookingStatus; }
    public void setBookingStatus(String bookingStatus) { this.bookingStatus = bookingStatus; }

    public int getCustomerId() { return customerId; }
    public void setCustomerId(int customerId) { this.customerId = customerId; }

    public int getTechnicianId() { return technicianId; }
    public void setTechnicianId(int technicianId) { this.technicianId = technicianId; }

    public String getCustomerName() { return customerName; }
    public void setCustomerName(String customerName) { this.customerName = customerName; }

    public String getCustomerAddress() { return customerAddress; }
    public void setCustomerAddress(String customerAddress) { this.customerAddress = customerAddress; }

    public String getCustomerPhonenum() { return customerPhonenum; }
    public void setCustomerPhonenum(String customerPhonenum) { this.customerPhonenum = customerPhonenum; }

    public String getCustomerEmail() { return customerEmail; }
    public void setCustomerEmail(String customerEmail) { this.customerEmail = customerEmail; }

    public String getServicesList() { return servicesList; }
    public void setServicesList(String servicesList) { this.servicesList = servicesList; }
}