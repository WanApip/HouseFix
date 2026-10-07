<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" isELIgnored="true" %>
<%@ page import="java.util.List" %>
<%@ page import="housefix.ServiceBean" %>
<%@ page import="housefix.AreaBean" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Services & Area Selection</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8eed3; }
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #ddd; 
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        }
        .header-logo { height: 50px; width: auto; display: block; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #f8eed3; font-weight: bold; }
        .container { padding: 100px 40px 40px; max-width: 900px; margin: 0 auto; }
        .section-title { font-size: 28px; margin-bottom: 20px; text-align: center; color: #333; }
        .area-section { background-color: #fbf7e9; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #dee2e6; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .grid-services { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .service-card { border: 1px solid #ccc; padding: 20px; border-radius: 8px; background: #fbf7e9; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .service-card input { transform: scale(1.3); margin-top: 10px; cursor: pointer; }
        .submit-container { text-align: center; }
        .btn-submit { background-color: #734d26; color: white; padding: 14px 30px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; font-weight: bold; }
        .btn-submit:hover { background-color: #593b1d; }
        
        .combined-dropdown { position: relative; width: 100%; margin-bottom: 15px; }
        .combined-dropdown input { width: 100%; padding: 12px; font-size: 16px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; background-color: white; }
        .dropdown-arrow { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 16px; color: #666; pointer-events: none; }
        .dropdown-options { position: absolute; top: 100%; left: 0; right: 0; max-height: 250px; overflow-y: auto; background: white; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; z-index: 100; display: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .dropdown-options.show { display: block; }
        .dropdown-option { padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #eee; text-align: left; color: #333; }
        .dropdown-option:hover, .dropdown-option.highlight { background-color: #d4edda; }
        .no-results { padding: 10px 12px; color: #d9534f; text-align: center; }
        .selected-area-info { margin-top: 10px; padding: 8px; background-color: #d4edda; border-radius: 5px; color: #155724; display: none; font-size: 14px; }
        .selected-area-info.show { display: block; }
        .search-hint { font-size: 12px; color: #666; margin-top: 5px; }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: 40px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="header">
        <form action="HouseFixController" method="GET" style="margin: 0;">
            <input type="hidden" name="action" value="home">
            <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
        </form>
        <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
        <div class="nav-links">
            <a href="tech_login.jsp">Login</a>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title">Choose Your Service</h2>

        <form action="HouseFixController" method="POST" id="serviceForm">
            <input type="hidden" name="action" value="findTechnician">
            
            <div class="area-section">
                <h3>Select Your Service Area:</h3>
                <div class="combined-dropdown">
                    <input type="text" id="area_search" placeholder="Search by postcode, town, or state..." autocomplete="off">
                    <span class="dropdown-arrow">▼</span>
                    <div id="dropdownOptions" class="dropdown-options"></div>
                </div>
                <div class="search-hint">Type to search | Click to select from list</div>
                
                <input type="hidden" name="area_id" id="area_id" required>
                <div id="selectedAreaInfo" class="selected-area-info"></div>
            </div>

            <div class="grid-services">
                <% 
                    @SuppressWarnings("unchecked")
                    List<ServiceBean> servicesList = (List<ServiceBean>) request.getAttribute("servicesList");
                    if (servicesList != null && !servicesList.isEmpty()) {
                        for (ServiceBean service : servicesList) {
                %>
                    <div class="service-card">
                        <h3><%= service.getServicesName() %></h3>
                        <p style="font-size: 14px; color: #666; min-height: 40px;"><%= service.getServicesDescription() %></p>
                        <p><strong>Base Rate: RM<%= String.format("%.2f", service.getServicesFee()) %></strong></p>
                        <input type="radio" name="selected_service" value="<%= service.getServicesId() %>" required>
                    </div>
                <% 
                        }
                    } else {
                %>
                    <p style="text-align:center; grid-column: 1/-1;">No services currently available.</p>
                <% } %>
            </div>

            <div class="submit-container">
                <button type="submit" class="btn-submit">Find Technician</button>
            </div>
        </form>
    </div>

    <script>
        const areas = [
            <% 
                @SuppressWarnings("unchecked")
                List<AreaBean> areasList = (List<AreaBean>) request.getAttribute("areasList");
                if (areasList != null) {
                    for (int i = 0; i < areasList.size(); i++) {
                        AreaBean area = areasList.get(i);
            %>
                {
                    id: <%= area.getAreaId() %>,
                    postcode: "<%= area.getPostcode() %>",
                    town: "<%= area.getTown() %>",
                    state: "<%= area.getState() %>",
                    display: "<%= area.getPostcode() %> - <%= area.getTown() %> (<%= area.getState() %>)"
                }<%= (i < areasList.size() - 1) ? "," : "" %>
            <% 
                    }
                } 
            %>
        ];
        
        const searchInput = document.getElementById('area_search');
        const dropdownOptions = document.getElementById('dropdownOptions');
        const hiddenAreaId = document.getElementById('area_id');
        const selectedInfo = document.getElementById('selectedAreaInfo');
        
        function renderOptions(filteredAreas, searchTerm = '') {
            if (filteredAreas.length === 0) {
                dropdownOptions.innerHTML = '<div class="no-results">❌ No matching area found</div>';
                dropdownOptions.classList.add('show');
                return;
            }
            
            let html = '';
            filteredAreas.forEach(area => {
                let displayText = area.display;
                if (searchTerm && searchTerm.length > 0) {
                    const escapedTerm = searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    const regex = new RegExp('(' + escapedTerm + ')', 'gi');
                    displayText = area.display.replace(regex, '<mark style="background: #ffeaa7; padding: 0;">$1</mark>');
                }
                const escapedDisplay = area.display.replace(/'/g, "\\'");
                html += '<div class="dropdown-option" onclick="selectArea(' + area.id + ', \'' + escapedDisplay + '\')">' +
                    '📍 ' + displayText +
                '</div>';
            });
            dropdownOptions.innerHTML = html;
            dropdownOptions.classList.add('show');
        }
        
        function filterAreas(searchTerm) {
            const term = searchTerm.toLowerCase().trim();
            if (term === '') {
                renderOptions(areas, '');
                return;
            }
            const filtered = areas.filter(area => 
                area.postcode.toLowerCase().includes(term) || 
                area.town.toLowerCase().includes(term) ||
                area.state.toLowerCase().includes(term)
            );
            renderOptions(filtered, term);
        }
        
        function selectArea(areaId, areaDisplay) {
            hiddenAreaId.value = areaId;
            searchInput.value = areaDisplay;
            selectedInfo.innerHTML = '✅ Selected area: ' + areaDisplay;
            selectedInfo.classList.add('show');
            dropdownOptions.classList.remove('show');
        }
        
        searchInput.addEventListener('input', function(e) { filterAreas(e.target.value); });
        searchInput.addEventListener('click', function(e) { e.stopPropagation(); filterAreas(searchInput.value); });
        document.addEventListener('click', function(e) {
            const container = document.querySelector('.combined-dropdown');
            if (container && !container.contains(e.target)) {
                dropdownOptions.classList.remove('show');
            }
        });
    </script>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact support@housefix.com
    </footer>
</body>
</html>