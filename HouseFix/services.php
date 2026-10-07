<?php
// Include database connection and session handling
include('dbconn.php');

// Fetch all available services from the database
$services_query = "SELECT * FROM SERVICES";
$services_result = $conn->query($services_query);

// Fetch all areas from the database for the location picker
$area_query = "SELECT * FROM AREA";
$area_result = $conn->query($area_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Service Page</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8eed3; }
        .header { 
            background-color: #734d26; 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #ddd; 
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .header-logo {
            height: 60px; /* Adjust this value to make it the right size */
            width: auto;   /* Maintains aspect ratio */
            display: block;
        }
        
        .logo { font-size: 24px; font-weight: bold; color: #f8eed3; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #734d26; }
        .container { padding: 40px; max-width: 900px; margin: 0 auto; }
        .section-title { font-size: 28px; margin-bottom: 20px; text-align: center; color: #333; }
        .area-section { background-color: #fbf7e9; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #dee2e6; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .grid-services { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .service-card { border: 1px solid #ccc; padding: 20px; border-radius: 8px; background: #fbf7e9; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .service-card input { transform: scale(1.3); margin-top: 10px; cursor: pointer; }
        .submit-container { text-align: center; }
        .btn-submit { background-color: #007bff; color: white; padding: 14px 30px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; }
        .btn-submit:hover { background-color: #0056b3; }
        
        /* Combined searchable dropdown - ONE TEXT AREA ONLY */
        .combined-dropdown {
            position: relative;
            width: 100%;
            margin-bottom: 15px;
        }
        .combined-dropdown input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: white;
            cursor: pointer;
        }
        .combined-dropdown input:focus {
            outline: none;
            border-color: #007bff;
        }
        .dropdown-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #666;
            pointer-events: none;
        }
        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 250px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            z-index: 100;
            display: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .dropdown-options.show {
            display: block;
        }
        .dropdown-option {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .dropdown-option:hover {
            background-color: #d4edda;
        }
        .dropdown-option.highlight {
            background-color: #007bff;
            color: white;
        }
        .no-results {
            padding: 10px 12px;
            color: #d9534f;
            text-align: center;
        }
        .selected-area-info {
            margin-top: 10px;
            padding: 8px;
            background-color: #d4edda;
            border-radius: 5px;
            color: #155724;
            display: none;
            font-size: 14px;
        }
        .selected-area-info.show {
            display: block;
        }
        .search-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .footer { background-color: #734d26; color: #f8eed3; text-align: center; padding: 20px; margin-top: auto; font-size: 14px; }
    </style>
</head>
<body>

    <!-- FIXED HEADER - Stays on top when scrolling -->
<div class="header">
    <form action="homePage.php" style="margin: 0;">
        <button type="submit" style="padding: 8px 16px; background-color: #f8eed3; font-weight: bold; color: #734d26; border: none; border-radius: 5px; cursor: pointer;">Back</button>
    </form>
    <img src="images/logo_header2.png" alt="HouseFix Logo" class="header-logo">
    <div class="nav-links">
        <a href="tech_login.php">Login</a>
    </div>
</div>

    <div class="container">
        <br><br><h2 class="section-title">Choose Your Service</h2>

        <form action="availableTechnician.php" method="POST" id="serviceForm">
            
            <div class="area-section">
                <h3>Select Your Service Area:</h3>
                
                <!-- COMBINED SEARCHABLE DROPDOWN - ONE TEXT AREA ONLY -->
                <div class="combined-dropdown">
                    <input type="text" id="area_search" placeholder="Search by postcode, town, or state..." autocomplete="off">
                    <span class="dropdown-arrow">▼</span>
                    <div id="dropdownOptions" class="dropdown-options"></div>
                </div>
                <div class="search-hint">Type to search | Click to select from list</div>
                
                <!-- Hidden input to store selected area ID -->
                <input type="hidden" name="area_id" id="area_id" required>
                
                <div id="selectedAreaInfo" class="selected-area-info"></div>
            </div>

            <div class="grid-services">
                <?php 
                $services_result = $conn->query($services_query);
                while($service = $services_result->fetch_assoc()) { 
                ?>
                    <div class="service-card">
                        <h3><?php echo htmlspecialchars($service['services_name']); ?></h3>
                        <p style="font-size: 14px; color: #666; min-height: 40px;"><?php echo htmlspecialchars($service['services_description']); ?></p>
                        <p><strong>Base Rate: RM<?php echo number_format($service['services_fee'], 2); ?></strong></p>
                        
                        <input type="radio" name="selected_service" value="<?php echo $service['services_id']; ?>" required>
                    </div>
                <?php 
                } 
                ?>
            </div>

            <div class="submit-container">
                <button type="submit" class="btn-submit" style="background-color: #734d26;">Find Technician</button>
            </div>

        </form>
    </div>

    <script>
        // Area data for search
        const areas = <?php
            $area_data = $conn->query("SELECT area_id, postcode, town, state FROM AREA ORDER BY postcode ASC");
            $areas_array = [];
            while($a = $area_data->fetch_assoc()) {
                $areas_array[] = [
                    'id' => $a['area_id'],
                    'postcode' => $a['postcode'],
                    'town' => $a['town'],
                    'state' => $a['state'],
                    'display' => $a['postcode'] . " - " . $a['town'] . " (" . $a['state'] . ")"
                ];
            }
            echo json_encode($areas_array);
        ?>;
        
        // DOM elements
        const searchInput = document.getElementById('area_search');
        const dropdownOptions = document.getElementById('dropdownOptions');
        const hiddenAreaId = document.getElementById('area_id');
        const selectedInfo = document.getElementById('selectedAreaInfo');
        
        let allAreas = areas;
        let isDropdownOpen = false;
        
        // Function to render dropdown options
        function renderOptions(filteredAreas, searchTerm = '') {
            if (filteredAreas.length === 0) {
                dropdownOptions.innerHTML = '<div class="no-results">❌ Invalid Postal Codes</div>';
                dropdownOptions.classList.add('show');
                isDropdownOpen = true;
                return;
            }
            
            let html = '';
            filteredAreas.forEach(area => {
                // Highlight matching text if search term exists
                let displayText = area.display;
                if (searchTerm && searchTerm.length > 0) {
                    const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                    displayText = area.display.replace(regex, '<mark style="background: #ffeaa7; padding: 0;">$1</mark>');
                }
                html += `<div class="dropdown-option" onclick="selectArea(${area.id}, '${area.display.replace(/'/g, "\\'")}')">
                    📍 ${displayText}
                </div>`;
            });
            dropdownOptions.innerHTML = html;
            dropdownOptions.classList.add('show');
            isDropdownOpen = true;
        }
        
        // Filter areas based on search term
        function filterAreas(searchTerm) {
            const term = searchTerm.toLowerCase().trim();
            
            if (term === '') {
                // Show all areas when search is empty
                renderOptions(allAreas, '');
                return;
            }
            
            const filtered = allAreas.filter(area => 
                area.postcode.toLowerCase().includes(term) || 
                area.town.toLowerCase().includes(term) ||
                area.state.toLowerCase().includes(term)
            );
            
            renderOptions(filtered, term);
        }
        
        // Select an area
        function selectArea(areaId, areaDisplay) {
            // Set hidden input value
            hiddenAreaId.value = areaId;
            
            // Set search input text
            searchInput.value = areaDisplay;
            
            // Show selected info
            selectedInfo.innerHTML = `✅ Selected area: ${areaDisplay}`;
            selectedInfo.classList.add('show');
            
            // Close dropdown
            dropdownOptions.classList.remove('show');
            isDropdownOpen = false;
        }
        
        // Handle input event (typing)
        searchInput.addEventListener('input', function(e) {
            filterAreas(e.target.value);
        });
        
        // Handle click on input to show dropdown
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
            if (hiddenAreaId.value === '') {
                // Show all areas when clicking empty input
                filterAreas(searchInput.value);
            } else {
                // If area already selected, show all areas when clicking
                filterAreas(searchInput.value);
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.querySelector('.combined-dropdown');
            if (container && !container.contains(e.target)) {
                dropdownOptions.classList.remove('show');
                isDropdownOpen = false;
            }
        });
        
        // Handle keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const options = document.querySelectorAll('.dropdown-option');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (options.length > 0) {
                    if (!dropdownOptions.classList.contains('show')) {
                        filterAreas(searchInput.value);
                    }
                    options[0].classList.add('highlight');
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const highlighted = document.querySelector('.dropdown-option.highlight');
                if (highlighted) {
                    highlighted.click();
                } else if (options.length === 1 && dropdownOptions.classList.contains('show')) {
                    options[0].click();
                }
            }
        });
        
        // Remove highlight on mouse move
        dropdownOptions.addEventListener('mousemove', function(e) {
            const highlighted = document.querySelector('.dropdown-option.highlight');
            if (highlighted) {
                highlighted.classList.remove('highlight');
            }
        });
        
        // Initialize - no dropdown shown initially
    </script>

    <footer class="footer">
        &copy; 2026 HouseFix. All rights reserved. | Contact us at support@housefix.com
    </footer>
</body>
</html>