package housefix;

import java.sql.Timestamp;

public class ReviewBean {
    private int reviewId;
    private int ratingScore;
    private String reviewComment;
    private int bookingId;
    private Timestamp reviewDate;
    
    // Additional Customer Detail
    private String customerName;

    public ReviewBean() {}

    public int getReviewId() { return reviewId; }
    public void setReviewId(int reviewId) { this.reviewId = reviewId; }

    public int getRatingScore() { return ratingScore; }
    public void setRatingScore(int ratingScore) { this.ratingScore = ratingScore; }

    public String getReviewComment() { return reviewComment; }
    public void setReviewComment(String reviewComment) { this.reviewComment = reviewComment; }

    public int getBookingId() { return bookingId; }
    public void setBookingId(int bookingId) { this.bookingId = bookingId; }

    public Timestamp getReviewDate() { return reviewDate; }
    public void setReviewDate(Timestamp reviewDate) { this.reviewDate = reviewDate; }

    public String getCustomerName() { return customerName; }
    public void setCustomerName(String customerName) { this.customerName = customerName; }
}