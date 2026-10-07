<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="housefix.TechnicianBean" %>
<%@ page import="housefix.ServiceBean" %>
<%
    TechnicianBean tech = (TechnicianBean) session.getAttribute("selectedTechnician");
    ServiceBean service = (ServiceBean) session.getAttribute("selectedService");
    Double baseFee = (Double) session.getAttribute("baseFee");
    Double platformFee = (Double) session.getAttribute("platformFee");
    Double totalFee = (Double) session.getAttribute("totalFee");

    if (tech == null) {
        response.sendRedirect("HouseFixController?action=showServices");
        return;
    }
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment | HouseFix</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8eed3; display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #ddd; 
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 1000;
        }
        
        .header-logo { height: 60px; width: auto; display: block; }
        .container { max-width: 900px; margin: 0 auto; width: 100%; box-sizing: border-box; padding: 100px 20px 40px 20px; }
        .invoice-box { border: 1px solid #ccc; padding: 30px; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .invoice-row { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .btn-pay { background-color: #734d26; color: white; padding: 14px; border: none; border-radius: 4px; width: 100%; font-weight: bold; cursor: pointer; margin-top: 15px; }
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:2000; }
        .modal-content { background:white; margin:10% auto; padding:25px; width:320px; border-radius:8px; text-align:center; }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 30px; margin-top: auto; }
        #qr-section, #cc-section { display: none; margin-top: 15px; padding: 10px; border: 1px solid #ddd; }
        #qr-radio:checked ~ #qr-section { display: block; }
        #cc-radio:checked ~ #cc-section { display: block; }
    </style>
</head>
<body>

    <div class="header">
        <form action="HouseFixController" method="GET" style="margin: 0;">
            <input type="hidden" name="action" value="home">
            <button type="submit" style="padding: 8px 16px; background-color: #734d26; color: #f8eed3; border: none; border-radius: 5px; cursor: pointer;">Home</button>
        </form>
        
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        
        <div class="nav-links">
            <a href="HouseFixController?action=techLogin" style="color: #f8eed3;"></a>
        </div>
    </div>

    <div class="container">
        <div class="invoice-box">
            <h2>Payment Summary</h2>
            <div class="invoice-row"><span>Technician:</span> <span><%= tech != null ? tech.getTechName() : "N/A" %></span></div>
            <div class="invoice-row"><span>Service:</span> <span><%= service != null ? service.getServicesName() : "N/A" %></span></div>
            <div class="invoice-row"><span>Base Fee:</span> <span>RM <%= String.format("%.2f", baseFee != null ? baseFee : 0.0) %></span></div>
            <div class="invoice-row"><span>Platform Fee:</span> <span>RM <%= String.format("%.2f", platformFee != null ? platformFee : 0.0) %></span></div>
            <div class="invoice-row"><span>Total:</span> <span>RM <%= String.format("%.2f", totalFee != null ? totalFee : 0.0) %></span></div>
            
            <button type="button" class="btn-pay" onclick="document.getElementById('paymentModal').style.display='block'">Pay & Confirm Booking</button>
        </div>
    </div>

    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <h3>Choose Payment Method</h3>
            <form action="HouseFixController" method="POST">
                <input type="hidden" name="action" value="executePayment">
                
                <input type="radio" name="method" id="qr-radio" value="qr" required> QR Pay
                <input type="radio" name="method" id="cc-radio" value="cc"> Credit Card<br>
                
                <div id="qr-section">
                    <img src="images/qr_image.jpg" alt="QR Code" style="width:150px;">
                    <p>Scan the code above to pay.</p>
                </div>
                
                <div id="cc-section">
                    <input type="text" name="bank_name" placeholder="Bank Name" style="width:90%; margin:5px;">
                    <input type="text" name="card_num" placeholder="Card Number" style="width:90%; margin:5px;">
                    <input type="text" name="cvv" placeholder="CVV" style="width:90%; margin:5px;">
                </div>
                <br>

                <button type="submit" class="btn-pay">Proceed Payment</button>
                <button type="button" class="btn-pay" onclick="document.getElementById('paymentModal').style.display='none'">Cancel</button>
            </form>
        </div>
    </div>

    <footer class="footer">&copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com</footer>

</body>
</html>