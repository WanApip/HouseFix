package housefix;

public class AreaBean {
    private int areaId;
    private String postcode;
    private String town;
    private String state;

    public AreaBean() {}

    public int getAreaId() { return areaId; }
    public void setAreaId(int areaId) { this.areaId = areaId; }

    public String getPostcode() { return postcode; }
    public void setPostcode(String postcode) { this.postcode = postcode; }

    public String getTown() { return town; }
    public void setTown(String town) { this.town = town; }

    public String getState() { return state; }
    public void setState(String state) { this.state = state; }
}
