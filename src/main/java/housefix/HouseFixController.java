package housefix;

import java.io.File;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URLEncoder;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.MultipartConfig;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import jakarta.servlet.http.Part;

@WebServlet("/HouseFixController")
@MultipartConfig(
    fileSizeThreshold = 1024 * 1024 * 1, // 1 MB
    maxFileSize = 1024 * 1024 * 10,      // 10 MB
    maxRequestSize = 1024 * 1024 * 15   // 15 MB
)
public class HouseFixController extends HttpServlet {
    private static final long serialVersionUID = 1L;

    private ServiceDAO serviceDAO;
    private AreaDAO areaDAO;
    private TechnicianDAO technicianDAO;
    private BookingDAO bookingDAO;
    private ReviewDAO reviewDAO;

    @Override
    public void init() throws ServletException {
        this.serviceDAO = new ServiceDAO();
        this.areaDAO = new AreaDAO();
        this.technicianDAO = new TechnicianDAO();
        this.bookingDAO = new BookingDAO();
        this.reviewDAO = new ReviewDAO();
    }

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        processRequest(request, response);
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        processRequest(request, response);
    }

    private void processRequest(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");

        String action = request.getParameter("action");
        if (action == null || action.trim().isEmpty()) {
            action = "home";
        }

        try {
            switch (action) {
                case "home":
                    request.getRequestDispatcher("index.jsp").forward(request, response);
                    break;

                case "showServices":
                    handleShowServices(request, response);
                    break;

                case "findTechnician":
                    handleFindTechnician(request, response);
                    break;

                case "prepareBooking":
                case "selectTechnician":
                    handleSelectTechnician(request, response);
                    break;

                case "processBookingDetails":
                    handleProcessBookingDetails(request, response);
                    break;

                case "executePayment":
                    handleExecutePayment(request, response);
                    break;

                case "trackBooking":
                    handleTrackBooking(request, response);
                    break;
                
                case "showReviewForm":
                    handleShowReviewForm(request, response);
                    break;
                    
                case "submitReview":
                    handleSubmitReview(request, response);
                    break;

                case "showTechRegister":
                    handleShowTechRegister(request, response);
                    break;

                case "processTechRegister":
                    handleProcessTechRegister(request, response);
                    break;

                case "showTechLogin":
                case "techLogin":
                    handleShowTechLogin(request, response);
                    break;

                case "processTechLogin":
                    handleProcessTechLogin(request, response);
                    break;

                // --- TECHNICIAN DASHBOARD ACTIONS ---
                case "techDashboard":
                    handleTechDashboard(request, response);
                    break;

                case "toggleAvailability":
                    handleToggleAvailability(request, response);
                    break;

                case "completeJob":
                    handleCompleteJob(request, response);
                    break;

                // --- NEW TECHNICIAN PROFILE ACTIONS ---
                case "editProfile":
                    handleEditProfile(request, response);
                    break;

                case "updateProfile":
                    handleUpdateProfile(request, response);
                    break;
                    
                case "manageServices":
                    handleManageServices(request, response);
                    break;

                case "updateServices":
                    handleUpdateServices(request, response);
                    break;
                
                case "upgradePremium":
                    request.getRequestDispatcher("upgrade_premium.jsp").forward(request, response);
                    break;
                
                case "processPremiumUpgrade":
                    handleProcessPremiumUpgrade(request, response);
                    break;
                    
                case "viewAnalytics":
                case "advancedAnalytics":
                    handleViewAnalytics(request, response);
                    break;
                    
                case "techLogout":
                    handleTechLogout(request, response);
                    break;
                    
                case "showAdminLogin":
                    request.getRequestDispatcher("admin_login.jsp").forward(request, response);
                    break;

                case "processAdminLogin":
                    handleAdminLogin(request, response);
                    break;
                
                case "adminDashboard":
                    handleAdminDashboard(request, response);
                    break;

                case "adminLogout":
                    handleAdminLogout(request, response);
                    break;
                 
                case "manageTechs":
                    handleManageTechs(request, response);
                    break;

                case "updateTechStatus":
                    handleUpdateTechStatus(request, response);
                    break;
                    
                case "viewTechDirectory":
                    handleViewTech(request, response);
                    break;

                case "platformStatistics":
                	handleShowStatistics(request, response);
                    break;
                    
                case "manageArea":
                    handleManageArea(request, response);
                    break;
                    
                case "addArea":
                    handleAddArea(request, response);
                    break;
                    
                case "manageAdminServices":
                    handleManageAdminServices(request, response);
                    break;
                    
                case "addService":
                    handleAddService(request, response);
                    break;
                    
                case "updateServiceFee":
                    handleUpdateServiceFee(request, response);
                    break;
                    
                case "viewBookings":
                    handleViewBookings(request, response);
                    break;

                default:
                    request.setAttribute("errorMessage", "Invalid action context requested: " + action);
                    request.getRequestDispatcher("error.jsp").forward(request, response);
                    break;
                    
                 
            }
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("exceptionDetails", e.getMessage() != null ? e.getMessage() : e.toString());
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }

    // ==========================================
    // EXISTING CUSTOMER & AUTH HANDLERS
    // ==========================================

    private void handleShowServices(HttpServletRequest request, HttpServletResponse response) throws Exception {
        List<ServiceBean> services = serviceDAO.getAllServices();
        List<AreaBean> areas = areaDAO.getAllAreas();

        request.setAttribute("servicesList", services);
        request.setAttribute("areasList", areas);

        request.getRequestDispatcher("services.jsp").forward(request, response);
    }

    private void handleFindTechnician(HttpServletRequest request, HttpServletResponse response) throws Exception {
        String selectedArea = request.getParameter("area_id");
        String selectedService = request.getParameter("selected_service");

        if (selectedArea != null && !selectedArea.trim().isEmpty() &&
            selectedService != null && !selectedService.trim().isEmpty()) {

            int areaId = Integer.parseInt(selectedArea);
            int serviceId = Integer.parseInt(selectedService);

            HttpSession session = request.getSession();
            session.setAttribute("booking_area_id", areaId);
            session.setAttribute("booking_service_id", serviceId);

            List<TechnicianBean> technicians = technicianDAO.getAvailableTechnicians(areaId, serviceId);

            request.setAttribute("techniciansList", technicians);
            request.setAttribute("areaId", areaId);
            request.setAttribute("serviceId", serviceId);
        }

        request.getRequestDispatcher("availableTechnician.jsp").forward(request, response);
    }

    private void handleSelectTechnician(HttpServletRequest request, HttpServletResponse response) throws Exception {
        String techIdStr = request.getParameter("technician_id");

        if (techIdStr != null && !techIdStr.trim().isEmpty()) {
            int techId = Integer.parseInt(techIdStr);

            TechnicianBean selectedTech = technicianDAO.getTechnicianById(techId);

            HttpSession session = request.getSession();
            session.setAttribute("booking_tech_id", techId);
            session.setAttribute("selectedTechnician", selectedTech);

            request.getRequestDispatcher("booking.jsp").forward(request, response);
        } else {
            response.sendRedirect("HouseFixController?action=showServices");
        }
    }

    private void handleProcessBookingDetails(HttpServletRequest request, HttpServletResponse response) throws Exception {
        HttpSession session = request.getSession();

        session.setAttribute("cust_name", request.getParameter("cust_name"));
        session.setAttribute("cust_phone", request.getParameter("cust_phone"));
        session.setAttribute("cust_email", request.getParameter("cust_email"));
        session.setAttribute("cust_address", request.getParameter("cust_address"));
        session.setAttribute("booking_date", request.getParameter("booking_date"));
        session.setAttribute("booking_time", request.getParameter("booking_time"));

        Integer serviceId = (Integer) session.getAttribute("booking_service_id");
        if (serviceId == null) serviceId = 1;

        ServiceBean service = serviceDAO.getServiceById(serviceId);
        double baseFee = service != null ? service.getServicesFee() : 0.00;
        double platformFee = 1.00;
        double totalFee = baseFee + platformFee;

        session.setAttribute("selectedService", service);
        session.setAttribute("baseFee", baseFee);
        session.setAttribute("platformFee", platformFee);
        session.setAttribute("totalFee", totalFee);

        request.getRequestDispatcher("payment.jsp").forward(request, response);
    }

    private void handleExecutePayment(HttpServletRequest request, HttpServletResponse response) throws Exception {
        HttpSession session = request.getSession();

        Integer techId = (Integer) session.getAttribute("booking_tech_id");
        Integer serviceId = (Integer) session.getAttribute("booking_service_id");
        String custName = (String) session.getAttribute("cust_name");
        String custPhone = (String) session.getAttribute("cust_phone");
        String custEmail = (String) session.getAttribute("cust_email");
        String custAddress = (String) session.getAttribute("cust_address");
        String bDate = (String) session.getAttribute("booking_date");
        String bTime = (String) session.getAttribute("booking_time");

        if (bookingDAO.isDoubleBooked(techId, bDate, bTime)) {
            request.setAttribute("errorMessage", "Error: This technician is already booked at that time.");
            request.getRequestDispatcher("booking.jsp").forward(request, response);
            return;
        }

        int customerId = bookingDAO.createCustomer(custName, custAddress, custPhone, custEmail);
        int bookingId = bookingDAO.createBooking(bDate, bTime, customerId, techId);
        bookingDAO.createBookingDetail(bookingId, serviceId);

        TechnicianBean tech = technicianDAO.getTechnicianById(techId);
        ServiceBean service = serviceDAO.getServiceById(serviceId);

        sendTelegramNotification(bookingId, custName, service != null ? service.getServicesName() : "General Fix",
                tech != null ? tech.getTechName() : "Expert", bDate, bTime, custAddress);

        response.sendRedirect("HouseFixController?action=trackBooking&booking_id=" + bookingId);
    }

    private void sendTelegramNotification(int bookingId, String name, String serviceName, String techName,
                                          String bDate, String bTime, String address) {
        try {
            String trackingUrl = "http://localhost:8080/HouseFix/HouseFixController?action=trackBooking&booking_id=" + bookingId;
            StringBuilder tgText = new StringBuilder();
            tgText.append("<b>⚡ HouseFix Booking Confirmed (Ref: #").append(bookingId).append(")</b>\n\n");
            tgText.append("Hello ").append(name).append(",\n");
            tgText.append("Your repair request has been logged successfully!\n\n");
            tgText.append("🛠️ <b>Service:</b> ").append(serviceName).append("\n");
            tgText.append("👨‍🔧 <b>Assigned Tech:</b> ").append(techName).append("\n");
            tgText.append("🗓️ <b>Appointment:</b> ").append(bDate).append(" @ ").append(bTime).append("\n");
            tgText.append("📍 <b>Address:</b> ").append(address).append("\n\n");
            tgText.append("🌐 <b>TRACK PROGRESS LIVE:</b>\n").append(trackingUrl);

            String botToken = "8838650204:AAFj6Pk8QMAcWIoKFJgG78Ly_1wgGjfAwIM";
            String chatId = "778931043";

            String urlStr = "https://api.telegram.org/bot" + botToken + "/sendMessage?chat_id=" + chatId 
                    + "&parse_mode=HTML&text=" + URLEncoder.encode(tgText.toString(), "UTF-8");

            HttpClient client = HttpClient.newHttpClient();
            HttpRequest request = HttpRequest.newBuilder().uri(URI.create(urlStr)).GET().build();
            client.sendAsync(request, HttpResponse.BodyHandlers.ofString());
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void handleTrackBooking(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        String bookingIdStr = request.getParameter("booking_id");
        int bookingId = Integer.parseInt(bookingIdStr);

        try {
            BookingBean booking = bookingDAO.getBookingById(bookingId);

            if (booking != null) {
                TechnicianBean tech = technicianDAO.getTechnicianById(booking.getTechnicianId());
                String techName = (tech != null) ? tech.getTechName() : "N/A";

                request.setAttribute("booking", booking);
                request.setAttribute("techName", techName);
                
                request.getRequestDispatcher("tracking.jsp").forward(request, response);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void handleShowTechRegister(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        try {
            List<AreaBean> areaList = areaDAO.getAllAreas();
            List<ServiceBean> serviceList = serviceDAO.getAllServices();

            request.setAttribute("areaList", areaList);
            request.setAttribute("serviceList", serviceList);

            request.getRequestDispatcher("tech_register.jsp").forward(request, response);
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error loading registration form.");
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }

    private void handleProcessTechRegister(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        String name = request.getParameter("tech_name");
        String email = request.getParameter("tech_email");
        String password = request.getParameter("password");
        String phone = request.getParameter("tech_phonenum");
        String ssm = request.getParameter("ssm_num");
        
        int areaId = Integer.parseInt(request.getParameter("area_id"));
        int serviceId = Integer.parseInt(request.getParameter("services_id"));

        try {
            boolean success = technicianDAO.registerTechnician(name, email, password, phone, ssm, areaId, serviceId);

            if (success) {
                response.setContentType("text/html;charset=UTF-8");
                PrintWriter out = response.getWriter();
                out.println("<script>");
                out.println("alert('Registration successful! Please wait for admin approval. You will now be redirected to the login page.');");
                out.println("window.location.href='HouseFixController?action=showTechLogin';");
                out.println("</script>");
            } else {
                request.setAttribute("errorMessage", "Registration failed. Please try again.");
                handleShowTechRegister(request, response);
            }
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error processing registration: " + e.getMessage());
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }

    private void handleShowTechLogin(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        request.getRequestDispatcher("tech_login.jsp").forward(request, response);
    }

    private void handleProcessTechLogin(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        String email = request.getParameter("email");
        String password = request.getParameter("password");

        try {
            TechnicianBean tech = technicianDAO.getTechnicianByEmail(email);

            if (tech != null) {
                if ("Approved".equalsIgnoreCase(tech.getTechStatus())) {
                    if (password.equals(tech.getPasswordHash())) {
                        HttpSession session = request.getSession();
                        session.setAttribute("technician_id", tech.getTechnicianId());
                        session.setAttribute("tech_name", tech.getTechName());

                        response.sendRedirect("HouseFixController?action=techDashboard");
                        return;
                    } else {
                        request.setAttribute("errorMessage", "Invalid password.");
                    }
                } else if ("Pending".equalsIgnoreCase(tech.getTechStatus())) {
                    request.setAttribute("errorMessage", "Your account is registered, but it is still under Admin review. Please wait for approval.");
                } else {
                    request.setAttribute("errorMessage", "Your account access has been restricted. Contact support.");
                }
            } else {
                request.setAttribute("errorMessage", "No account found with this email address.");
            }
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "An error occurred during login. Please try again.");
        }

        request.getRequestDispatcher("tech_login.jsp").forward(request, response);
    }

    // ==========================================
    // TECHNICIAN DASHBOARD HANDLERS
    // ==========================================

    private void handleTechDashboard(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId == null) {
            response.sendRedirect("HouseFixController?action=showTechLogin");
            return;
        }

        try {
            TechnicianBean tech = technicianDAO.getTechnicianById(techId);
            if (tech == null) {
                request.setAttribute("errorMessage", "Technician profile not found.");
                request.getRequestDispatcher("error.jsp").forward(request, response);
                return;
            }

            // Area resolution
            String locationDisplay = "Not specified";
            if (tech.getAreaId() != null && tech.getAreaId() > 0) {
                AreaBean area = areaDAO.getAreaById(tech.getAreaId());
                if (area != null) {
                    locationDisplay = area.getTown() + ", " + area.getState();
                }
            }

            // Quotas
            int monthJobs = technicianDAO.getMonthlyJobCount(techId);
            int jobLimit = 10;
            boolean isBasic = "Basic".equalsIgnoreCase(tech.getPlanType());
            boolean limitReached = isBasic && (monthJobs >= jobLimit);

            // Fetch lists
            List<ServiceBean> servicesList = serviceDAO.getServiceByTechId(techId);
            List<BookingBean> assignedJobsList = bookingDAO.getPendingJobsByTechId(techId);
            List<ReviewBean> reviewsList = reviewDAO.getReviewsByTechId(techId);

            request.setAttribute("tech", tech);
            request.setAttribute("locationDisplay", locationDisplay);
            request.setAttribute("monthJobs", monthJobs);
            request.setAttribute("jobLimit", jobLimit);
            request.setAttribute("isBasic", isBasic);
            request.setAttribute("limitReached", limitReached);
            request.setAttribute("servicesList", servicesList);
            request.setAttribute("assignedJobsList", assignedJobsList);
            request.setAttribute("reviewsList", reviewsList);

            request.getRequestDispatcher("tech_dashboard.jsp").forward(request, response);

        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error loading technician dashboard: " + e.getMessage());
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }

    private void handleToggleAvailability(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId != null) {
            technicianDAO.toggleAvailability(techId);
        }
        response.sendRedirect("HouseFixController?action=techDashboard");
    }

    private void handleCompleteJob(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        String bookingIdStr = request.getParameter("bookingId");
        if (bookingIdStr != null && !bookingIdStr.trim().isEmpty()) {
            try {
                int bookingId = Integer.parseInt(bookingIdStr);
                bookingDAO.updateBookingStatus(bookingId, "Completed");
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
        response.sendRedirect("HouseFixController?action=techDashboard");
    }

    // ==========================================
    // NEW TECHNICIAN PROFILE HANDLERS
    // ==========================================

    private void handleEditProfile(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId == null) {
            response.sendRedirect("HouseFixController?action=showTechLogin");
            return;
        }

        try {
            TechnicianBean tech = technicianDAO.getTechnicianById(techId);
            request.setAttribute("tech", tech);
            request.getRequestDispatcher("edit_profile.jsp").forward(request, response);
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error opening edit profile screen: " + e.getMessage());
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }

    private void handleUpdateProfile(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId == null) {
            response.sendRedirect("HouseFixController?action=showTechLogin");
            return;
        }

        try {
            TechnicianBean tech = technicianDAO.getTechnicianById(techId);

            String name = request.getParameter("tech_name");
            String phone = request.getParameter("tech_phonenum");
            String photoPath = tech.getPhotoPath();

            // Handle file attachment for new picture
            Part filePart = request.getPart("photo");
            if (filePart != null && filePart.getSize() > 0) {
                String fileName = System.currentTimeMillis() + "_" + extractFileName(filePart);
                
                String uploadPath = getServletContext().getRealPath("") + File.separator + "uploads";
                File uploadDir = new File(uploadPath);
                if (!uploadDir.exists()) uploadDir.mkdir();

                filePart.write(uploadPath + File.separator + fileName);

                // Clean up previous image file if it wasn't default
                if (photoPath != null && !photoPath.equalsIgnoreCase("uploads/technician.png")) {
                    File oldFile = new File(getServletContext().getRealPath("") + File.separator + photoPath);
                    if (oldFile.exists()) oldFile.delete();
                }

                photoPath = "uploads/" + fileName;
            }

            tech.setTechName(name);
            tech.setTechPhonenum(phone);
            tech.setPhotoPath(photoPath);

            boolean isUpdated = technicianDAO.updateTechnicianProfile(tech);

            if (isUpdated) {
                session.setAttribute("tech_name", tech.getTechName()); // Update session state
                request.setAttribute("success", "Profile updated successfully!");
            } else {
                request.setAttribute("error", "Failed to update profile. Please try again.");
            }

            request.setAttribute("tech", tech);
            request.getRequestDispatcher("edit_profile.jsp").forward(request, response);

        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error updating profile: " + e.getMessage());
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }

    private String extractFileName(Part part) {
        String contentDisp = part.getHeader("content-disposition");
        for (String content : contentDisp.split(";")) {
            if (content.trim().startsWith("filename")) {
                String fileName = content.substring(content.indexOf("=") + 2, content.length() - 1);
                return fileName.substring(fileName.lastIndexOf('/') + 1).substring(fileName.lastIndexOf('\\') + 1);
            }
        }
        return "photo.jpg";
    }
    
    private void handleManageServices(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId == null) {
            response.sendRedirect("HouseFixController?action=showTechLogin");
            return;
        }

        try {
            List<ServiceBean> allServices = serviceDAO.getAllServices();
            List<ServiceBean> techServices = serviceDAO.getServiceByTechId(techId);

            request.setAttribute("allServices", allServices);
            request.setAttribute("techServices", techServices);
            request.getRequestDispatcher("manage_services.jsp").forward(request, response);
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error loading services: " + e.getMessage());
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }

    private void handleUpdateServices(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId == null) {
            response.sendRedirect("HouseFixController?action=showTechLogin");
            return;
        }

        try {
            String[] selectedServices = request.getParameterValues("services");

            // Remove previous links & save current ones via DAO
            serviceDAO.updateTechnicianServices(techId, selectedServices);

            response.sendRedirect("HouseFixController?action=techDashboard&msg=ServicesUpdated");
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error updating services: " + e.getMessage());
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }
    
    private void handleProcessPremiumUpgrade(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId == null) {
            response.sendRedirect("HouseFixController?action=showTechLogin");
            return;
        }

        // Process payment simulation
        boolean paymentSuccessful = true; // Simulated success

        if (paymentSuccessful) {
            boolean updated = technicianDAO.upgradeToPremium(techId);

            if (updated) {
                // Forward to success view
                request.getRequestDispatcher("payment_success.jsp").forward(request, response);
            } else {
                request.setAttribute("errorMessage", "Error updating account plan in the database.");
                request.getRequestDispatcher("error.jsp").forward(request, response);
            }
        } else {
            request.setAttribute("errorMessage", "Payment failed. Please try again.");
            request.getRequestDispatcher("error.jsp").forward(request, response);
        }
    }
    
    private void handleViewAnalytics(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        HttpSession session = request.getSession();
        Integer techId = (Integer) session.getAttribute("technician_id");

        if (techId == null) {
            response.sendRedirect("HouseFixController?action=showTechLogin");
            return;
        }

        // Fetch analytical data via DAO
        double totalRevenue = bookingDAO.getTotalRevenueByTechId(techId);
        List<CategoryAnalyticsBean> categoryAnalytics = bookingDAO.getCategoryAnalyticsByTechId(techId);

        // Pass attributes to JSP
        request.setAttribute("totalRevenue", totalRevenue);
        request.setAttribute("categoryAnalytics", categoryAnalytics);

        request.getRequestDispatcher("advanced_analytics.jsp").forward(request, response);
    }
    
    private void handleTechLogout(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        // Retrieve current session if it exists (false = don't create a new one if null)
        HttpSession session = request.getSession(false);

        if (session != null) {
            // Clears session attributes and invalidates session (Equivalent to session_unset & session_destroy)
            session.invalidate(); 
        }

        // Redirect to the technician login page/action
        response.sendRedirect("HouseFixController?action=showTechLogin");
    }
    
    private void handleAdminLogin(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        String username = request.getParameter("username");
        String password = request.getParameter("password");

        AdminDAO adminDAO = new AdminDAO();
        AdminBean admin = adminDAO.authenticateAdmin(username, password);

        if (admin != null) {
            HttpSession session = request.getSession();
            session.setAttribute("admin_id", admin.getAdminId());
            session.setAttribute("admin_user", admin.getUsernameAdmin());

            response.sendRedirect("HouseFixController?action=adminDashboard");
        } else {
            request.setAttribute("errorMessage", "Invalid username or password.");
            request.getRequestDispatcher("admin_login.jsp").forward(request, response);
        }
    }
    
    private void handleAdminDashboard(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        AdminDAO adminDAO = new AdminDAO();
        Map<String, Integer> stats = adminDAO.getDashboardStats();

        request.setAttribute("stats", stats);
        request.getRequestDispatcher("admin_dashboard.jsp").forward(request, response);
    }

    private void handleAdminLogout(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        HttpSession session = request.getSession(false);
        if (session != null) {
            session.invalidate();
        }
        response.sendRedirect("HouseFixController?action=showAdminLogin");
    }
    
    private void handleManageTechs(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        // Check Session (Admin Protection)
        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        // Fetch technicians list from DAO
        TechnicianDAO techDAO = new TechnicianDAO();
        List<TechnicianBean> allTechs = techDAO.getAllTechniciansWithServices();

        List<TechnicianBean> pending = new ArrayList<>();
        List<TechnicianBean> active = new ArrayList<>();

        // Filter pending vs active/suspended
        for (TechnicianBean tech : allTechs) {
            if ("Pending".equalsIgnoreCase(tech.getTechStatus())) {
                pending.add(tech);
            } else {
                active.add(tech);
            }
        }

        // Pass data to JSP
        request.setAttribute("pendingTechs", pending);
        request.setAttribute("activeTechs", active);
        request.getRequestDispatcher("manage_techs.jsp").forward(request, response);
    }

    private void handleUpdateTechStatus(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        String actionParam = request.getParameter("statusAction");
        String idParam = request.getParameter("id");

        if (idParam != null && actionParam != null) {
            int techId = Integer.parseInt(idParam);
            String newStatus;

            if ("approve".equalsIgnoreCase(actionParam)) {
                newStatus = "Approved";
            } else if ("reject".equalsIgnoreCase(actionParam)) {
                newStatus = "Rejected";
            } else {
                newStatus = "Suspended";
            }

            TechnicianDAO techDAO = new TechnicianDAO();
            techDAO.updateTechnicianStatus(techId, newStatus);
        }

        // Redirect back to refresh list
        response.sendRedirect("HouseFixController?action=manageTechs");
    }
    
    private void handleViewTech(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        TechnicianDAO techDAO = new TechnicianDAO();
        List<ServiceGroupBean> servicesDirectory = techDAO.getServicesDirectory();

        request.setAttribute("servicesDirectory", servicesDirectory);
        request.getRequestDispatcher("view_tech.jsp").forward(request, response);
    }
    
    private void handleShowStatistics(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        // Admin Session Security Check
        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        StatisticsDAO statsDAO = new StatisticsDAO();
        
        double premiumRevenue = statsDAO.getPremiumRevenue();
        double webUsageRevenue = statsDAO.getWebUsageRevenue();
        Map<String, Integer> serviceDemand = statsDAO.getServiceDemand();

        // Convert Map into JSON arrays for Chart.js
        StringBuilder serviceLabelsJson = new StringBuilder("[");
        StringBuilder serviceCountsJson = new StringBuilder("[");
        
        int index = 0;
        for (Map.Entry<String, Integer> entry : serviceDemand.entrySet()) {
            serviceLabelsJson.append("\"").append(entry.getKey().replace("\"", "\\\"")).append("\"");
            serviceCountsJson.append(entry.getValue());
            
            if (index < serviceDemand.size() - 1) {
                serviceLabelsJson.append(",");
                serviceCountsJson.append(",");
            }
            index++;
        }
        serviceLabelsJson.append("]");
        serviceCountsJson.append("]");

        // Attach data to request
        request.setAttribute("premiumRevenue", premiumRevenue);
        request.setAttribute("webUsageRevenue", webUsageRevenue);
        request.setAttribute("totalRevenue", premiumRevenue + webUsageRevenue);
        request.setAttribute("serviceLabelsJson", serviceLabelsJson.toString());
        request.setAttribute("serviceCountsJson", serviceCountsJson.toString());

        // Forward to JSP page
        request.getRequestDispatcher("admin_statistics.jsp").forward(request, response);
    }
    
 // Display Manage Area page (GET)
    private void handleManageArea(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        try {
            AreaDAO areaDAO = new AreaDAO();
            List<AreaBean> areaList = areaDAO.getAllAreas();
            request.setAttribute("areaList", areaList);
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error loading areas: " + e.getMessage());
        }

        request.getRequestDispatcher("manage_area.jsp").forward(request, response);
    }

 // Process Add Area form (POST)
    private void handleAddArea(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        String postcode = request.getParameter("postcode");
        String town = request.getParameter("town");
        String state = request.getParameter("state");

        if (postcode != null && town != null && state != null) {
            postcode = postcode.trim();
            town = town.trim();
            state = state.trim();

            try {
                AreaDAO areaDAO = new AreaDAO();
                
                // 1. Check for duplicate postcode
                if (areaDAO.existsPostcode(postcode)) {
                    response.sendRedirect("HouseFixController?action=manageArea&error=duplicate_postcode");
                    return;
                }

                // 2. Insert new area record
                AreaBean area = new AreaBean();
                area.setPostcode(postcode);
                area.setTown(town);
                area.setState(state);

                areaDAO.addArea(area);
                
                // 3. Redirect with success message
                response.sendRedirect("HouseFixController?action=manageArea&success=added");
                return;

            } catch (Exception e) {
                e.printStackTrace();
                response.sendRedirect("HouseFixController?action=manageArea&error=failed");
                return;
            }
        }

        // Fallback redirect if parameters were missing
        response.sendRedirect("HouseFixController?action=manageArea");
    }
    
 // 1. Display Manage Services page (GET)
    private void handleManageAdminServices(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        try {
            ServiceDAO serviceDAO = new ServiceDAO();
            List<ServiceBean> serviceList = serviceDAO.getAllServices();
            request.setAttribute("serviceList", serviceList);
        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error loading services: " + e.getMessage());
        }

        request.getRequestDispatcher("manage_adminservices.jsp").forward(request, response);
    }

    // 2. Process Add Service form (POST)
    private void handleAddService(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        String name = request.getParameter("services_name");
        String desc = request.getParameter("services_description");
        String feeStr = request.getParameter("services_fee");

        if (name != null && desc != null && feeStr != null) {
            try {
                ServiceDAO serviceDAO = new ServiceDAO();
                
                // Duplicate check
                if (serviceDAO.existsServiceName(name)) {
                    response.sendRedirect("HouseFixController?action=manageAdminServices&error=duplicate_name");
                    return;
                }

                ServiceBean service = new ServiceBean();
                service.setServicesName(name.trim());
                service.setServicesDescription(desc.trim());
                service.setServicesFee(Double.parseDouble(feeStr));

                serviceDAO.addService(service);
                response.sendRedirect("HouseFixController?action=manageAdminServices&success=added");
                return;

            } catch (Exception e) {
                e.printStackTrace();
                response.sendRedirect("HouseFixController?action=manageAdminServices&error=failed");
                return;
            }
            
        }

        response.sendRedirect("HouseFixController?action=manageAdminServices");
    }

    // 3. Process Update Fee form (POST)
    private void handleUpdateServiceFee(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        String idStr = request.getParameter("services_id");
        String feeStr = request.getParameter("new_fee");

        if (idStr != null && feeStr != null) {
            try {
                int servicesId = Integer.parseInt(idStr);
                double newFee = Double.parseDouble(feeStr);

                ServiceDAO serviceDAO = new ServiceDAO();
                serviceDAO.updateServiceFee(servicesId, newFee);

                response.sendRedirect("HouseFixController?action=manageAdminServices&success=updated");
                return;

            } catch (Exception e) {
                e.printStackTrace();
                response.sendRedirect("HouseFixController?action=manageAdminServices&error=update_failed");
                return;
            }
            
        }

        response.sendRedirect("HouseFixController?action=manageAdminServices");
    }
    
 // Handler method
    private void handleViewBookings(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("admin_id") == null) {
            response.sendRedirect("HouseFixController?action=showAdminLogin");
            return;
        }

        try {
            BookingDAO bookingDAO = new BookingDAO();
            List<BookingBean> allBookings = bookingDAO.getAllBookingsWithDetails();

            List<BookingBean> pendingList = new ArrayList<>();
            List<BookingBean> completedList = new ArrayList<>();

            for (BookingBean b : allBookings) {
                if ("Pending".equalsIgnoreCase(b.getBookingStatus())) {
                    pendingList.add(b);
                } else {
                    completedList.add(b);
                }
                
            }

            request.setAttribute("pendingList", pendingList);
            request.setAttribute("completedList", completedList);

        } catch (Exception e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Error loading bookings: " + e.getMessage());
        }

        request.getRequestDispatcher("view_booking.jsp").forward(request, response);
    }
    
    private void handleShowReviewForm(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        String bookingIdStr = request.getParameter("booking_id");
        int bookingId = 0;

        if (bookingIdStr != null && !bookingIdStr.trim().isEmpty()) {
            try {
                bookingId = Integer.parseInt(bookingIdStr);
            } catch (NumberFormatException e) {
                bookingId = 0;
            }
        }

        request.setAttribute("bookingId", bookingId);
        request.getRequestDispatcher("review.jsp").forward(request, response);
    }

    private void handleSubmitReview(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {

        String bookingIdStr = request.getParameter("booking_id");
        String ratingStr = request.getParameter("rating");
        String comment = request.getParameter("comment");

        if (bookingIdStr != null && ratingStr != null && comment != null) {
            try {
                int bookingId = Integer.parseInt(bookingIdStr);
                int rating = Integer.parseInt(ratingStr);

                ReviewBean review = new ReviewBean();
                review.setBookingId(bookingId);
                review.setRatingScore(rating);
                review.setReviewComment(comment.trim());

                ReviewDAO reviewDAO = new ReviewDAO();
                boolean isAdded = reviewDAO.addReview(review);

                if (isAdded) {
                    response.sendRedirect("HouseFixController?action=showReviewForm&booking_id=" + bookingId + "&success=true");
                    return;
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        }

        response.sendRedirect("HouseFixController?action=showReviewForm&error=true");
    }
}