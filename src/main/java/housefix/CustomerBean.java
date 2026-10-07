package housefix;

public class CustomerBean {
    private int customerId;
    private String customerName;
    private String customerAddress;
    private String customerPhoneNum;
    private String customerEmail;

    public CustomerBean() {}

    public int getCustomerId() { return customerId; }
    public void setCustomerId(int customerId) { this.customerId = customerId; }

    public String getCustomerName() { return customerName; }
    public void setCustomerName(String customerName) { this.customerName = customerName; }

    public String getCustomerAddress() { return customerAddress; }
    public void setCustomerAddress(String customerAddress) { this.customerAddress = customerAddress; }

    public String getCustomerPhoneNum() { return customerPhoneNum; }
    public void setCustomerPhoneNum(String customerPhoneNum) { this.customerPhoneNum = customerPhoneNum; }

    public String getCustomerEmail() { return customerEmail; }
    public void setCustomerEmail(String customerEmail) { this.customerEmail = customerEmail; }
}
