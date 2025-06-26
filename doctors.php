<?php
session_start();
include("db.php");

// Check if this is an AJAX request for filtering
if (isset($_POST['action']) && $_POST['action'] == 'filter') {
    $specialization = isset($_POST['specialization']) ? $conn->real_escape_string($_POST['specialization']) : '';
    $city = isset($_POST['city']) ? $conn->real_escape_string($_POST['city']) : '';
    $search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';

    $query = "SELECT * FROM doctors WHERE status = 'approved'";

    if ($specialization != '') {
        $query .= " AND specialization LIKE '%$specialization%'";
    }
    if ($city != '') {
        $query .= " AND city LIKE '%$city%'";
    }
    if ($search != '') {
        $query .= " AND (name LIKE '%$search%' OR specialization LIKE '%$search%' OR city LIKE '%$search%')";
    }

    $query .= " ORDER BY id DESC";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-12 mb-3">
                <div class="doctor-profile-card">
                    <div class="doctor-image-container">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    </div>
                    <div class="doctor-content-container">
                        <div class="doctor-main-info">
                            <div class="basic-info">
                                <h2 class="doctor-name"><?= htmlspecialchars($row['name']) ?></h2>
                                <span class="specialization"><?= htmlspecialchars($row['specialization']) ?></span>
                                
                                <div class="doctor-meta-info">
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= htmlspecialchars($row['city']) ?>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-phone-alt"></i>
                                        <?= htmlspecialchars($row['phone']) ?>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        <?= htmlspecialchars($row['experience']) ?> years experience
                                    </div>
                                </div>
                            </div>
                            
                            <div class="doctor-description">
                                <h4 class="text-primary mb-2">About Dr. <?= explode(' ', htmlspecialchars($row['name']))[0] ?></h4>
                                <div class="description-text">
                                    <?= htmlspecialchars($row['description']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="doctor-buttons">
                            <button class="btn-details toggle-details" data-doctor-id="<?= $row['id'] ?>">
                                <i class="fas fa-info-circle"></i> Full Profile
                            </button>
                            <a href="appointment.php?doctor_id=<?= $row['id'] ?>" class="btn-appointment">
                                <i class="fas fa-calendar-check"></i> Book Appointment
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Details Panel (hidden by default) -->
                <div class="doctor-details-panel" id="details-<?= $row['id'] ?>">
                    <div class="details-grid">
                        <div class="detail-item">
                            <strong><i class="fas fa-hospital"></i> Hospital</strong>
                            <span><?= htmlspecialchars($row['hospital_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <strong><i class="far fa-calendar-alt"></i> Available Days</strong>
                            <span><?= htmlspecialchars($row['days']) ?></span>
                        </div>
                        <div class="detail-item">
                            <strong><i class="far fa-clock"></i> Timing</strong>
                            <span><?= htmlspecialchars($row['timing']) ?></span>
                        </div>
                        <div class="detail-item">
                            <strong><i class="fas fa-graduation-cap"></i> Qualifications</strong>
                            <span><?= htmlspecialchars($row['degree']) ?></span>
                        </div>
                        <div class="detail-item">
                            <strong><i class="fas fa-map-marker-alt"></i> Clinic Address</strong>
                            <span><?= htmlspecialchars($row['address']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>No doctors found</h4>
                    <p class="mb-0">Try adjusting your search filters</p>
                </div>
              </div>';
    }
    exit;  // Important to exit after AJAX response
}

// For normal page load, fetch all approved doctors (initial load)
$result = $conn->query("SELECT * FROM doctors WHERE status = 'approved' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CARE - Hospital</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">  

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Filter form styles */
        #filterForm {
            max-width: 1000px;
            margin: 30px auto;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Single Card Per Row Styling */
        .doctor-profile-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
            background: #fff;
            display: flex;
            flex-direction: row;
            min-height: 250px;
        }

        .doctor-profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .doctor-image-container {
            width: 250px;
            min-width: 250px;
            height: 250px;
            overflow: hidden;
            position: relative;
            flex-shrink: 0;
        }

        .doctor-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
            transition: transform 0.5s ease;
        }

        .doctor-profile-card:hover .doctor-image-container img {
            transform: scale(1.03);
        }

        .doctor-content-container {
            flex-grow: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .doctor-main-info {
            margin-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .basic-info {
            flex: 1;
            min-width: 250px;
        }

        .doctor-description {
            flex: 2;
            min-width: 300px;
            padding: 0 1rem;
        }

        .doctor-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .specialization {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .doctor-meta-info {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #555;
        }

        .meta-item i {
            margin-right: 8px;
            color: #3498db;
            width: 16px;
            text-align: center;
            font-size: 0.9rem;
        }

        .doctor-buttons {
            margin-top: auto;
            display: flex;
            gap: 12px;
        }

        .btn-details {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border: 2px solid #1976d2;
            color: #1976d2;
            background: transparent;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-details:hover {
            background: #1976d2;
            color: white;
            transform: translateY(-2px);
        }

        .btn-appointment {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s;
            background: linear-gradient(135deg, #1976d2, #2196f3);
            border: none;
            color: white;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-appointment:hover {
            background: linear-gradient(135deg, #1565c0, #1976d2);
            transform: translateY(-2px);
            color: white;
        }

        .description-text {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Doctor Details Section */
        .doctor-details-panel {
            width: 100%;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, padding 0.3s ease;
        }

        .doctor-details-panel.show {
            padding: 1.5rem;
            max-height: 1000px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .detail-item {
            margin-bottom: 0.5rem;
        }

        .detail-item strong {
            color: #2c3e50;
            font-weight: 600;
            display: block;
            margin-bottom: 0.2rem;
        }

        .detail-item span {
            color: #555;
            font-size: 0.9rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .doctor-main-info {
                flex-direction: column;
                gap: 1rem;
            }
            
            .doctor-description {
                padding: 0;
            }
        }

        @media (max-width: 768px) {
            .doctor-profile-card {
                flex-direction: column;
                min-height: auto;
            }
            
            .doctor-image-container {
                width: 100%;
                height: 200px;
            }
            
            .doctor-buttons {
                margin-top: 1.5rem;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .doctor-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-details, .btn-appointment {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    
    <!-- Navbar Start -->
    <?php include("nav.php"); ?>
    <!-- Navbar End -->

    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <!-- Filter & Search Form -->
            <div class="container" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by name, specialization, city...">
                    </div>
                    <div class="col-md-3">
                        <select id="specializationFilter" class="form-select">
                            <option value="">Select Specialization</option>
                            <?php
                            $specRes = $conn->query("SELECT DISTINCT specialization FROM doctors WHERE status = 'approved' ORDER BY specialization ASC");
                            while ($specRow = $specRes->fetch_assoc()) {
                                echo '<option value="'.htmlspecialchars($specRow['specialization']).'">'.htmlspecialchars($specRow['specialization']).'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="cityFilter" class="form-select">
                            <option value="">Select City</option>
                            <?php
                            $cityRes = $conn->query("SELECT DISTINCT city FROM doctors WHERE status = 'approved' ORDER BY city ASC");
                            while ($cityRow = $cityRes->fetch_assoc()) {
                                echo '<option value="'.htmlspecialchars($cityRow['city']).'">'.htmlspecialchars($cityRow['city']).'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button id="resetFilter" class="btn btn-secondary w-100">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Doctors List Container - Single card per row -->
            <div class="row mt-5" id="doctorsList">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-12 mb-3">
                            <div class="doctor-profile-card">
                                <div class="doctor-image-container">
                                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                                </div>
                                <div class="doctor-content-container">
                                    <div class="doctor-main-info">
                                        <div class="basic-info">
                                            <h2 class="doctor-name"><?= htmlspecialchars($row['name']) ?></h2>
                                            <span class="specialization"><?= htmlspecialchars($row['specialization']) ?></span>
                                            
                                            <div class="doctor-meta-info">
                                                <div class="meta-item">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <?= htmlspecialchars($row['city']) ?>
                                                </div>
                                                <div class="meta-item">
                                                    <i class="fas fa-phone-alt"></i>
                                                    <?= htmlspecialchars($row['phone']) ?>
                                                </div>
                                                <div class="meta-item">
                                                    <i class="fas fa-briefcase"></i>
                                                    <?= htmlspecialchars($row['experience']) ?> years experience
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="doctor-description">
                                            <h4 class="text-primary mb-2">About Dr. <?= explode(' ', htmlspecialchars($row['name']))[0] ?></h4>
                                            <div class="description-text">
                                                <?= htmlspecialchars($row['description']) ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="doctor-buttons">
                                        <button class="btn-details toggle-details" data-doctor-id="<?= $row['id'] ?>">
                                            <i class="fas fa-info-circle"></i> Full Profile
                                        </button>
                                        <a href="appointment.php?doctor_id=<?= $row['id'] ?>" class="btn-appointment">
                                            <i class="fas fa-calendar-check"></i> Book Appointment
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Details Panel (hidden by default) -->
                            <div class="doctor-details-panel" id="details-<?= $row['id'] ?>">
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <strong><i class="fas fa-hospital"></i> Hospital</strong>
                                        <span><?= htmlspecialchars($row['hospital_name']) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <strong><i class="far fa-calendar-alt"></i> Available Days</strong>
                                        <span><?= htmlspecialchars($row['days']) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <strong><i class="far fa-clock"></i> Timing</strong>
                                        <span><?= htmlspecialchars($row['timing']) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <strong><i class="fas fa-graduation-cap"></i> Qualifications</strong>
                                        <span><?= htmlspecialchars($row['degree']) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <strong><i class="fas fa-map-marker-alt"></i> Clinic Address</strong>
                                        <span><?= htmlspecialchars($row['address']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h4>No doctors found</h4>
                            <p class="mb-0">Try adjusting your search filters</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <?php include("footer.php"); ?>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to bind toggle buttons
        function bindToggleButtons() {
            document.querySelectorAll('.toggle-details').forEach(button => {
                button.addEventListener('click', function() {
                    const doctorId = this.getAttribute('data-doctor-id');
                    const detailsPanel = document.getElementById(`details-${doctorId}`);
                    
                    // Toggle the show class
                    detailsPanel.classList.toggle('show');
                    
                    // Update button text
                    if (detailsPanel.classList.contains('show')) {
                        this.innerHTML = '<i class="fas fa-times"></i> Close Profile';
                    } else {
                        this.innerHTML = '<i class="fas fa-info-circle"></i> Full Profile';
                    }
                });
            });
        }
        
        // Initial binding
        bindToggleButtons();

        // Filter elements
        const searchInput = document.getElementById('searchInput');
        const specializationFilter = document.getElementById('specializationFilter');
        const cityFilter = document.getElementById('cityFilter');
        const resetBtn = document.getElementById('resetFilter');
        const doctorsList = document.getElementById('doctorsList');

        // Function to fetch filtered doctors via AJAX
        function fetchDoctors() {
            const data = new FormData();
            data.append('action', 'filter');
            data.append('search', searchInput.value.trim());
            data.append('specialization', specializationFilter.value);
            data.append('city', cityFilter.value);

            fetch(window.location.href, {
                method: 'POST',
                body: data
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                doctorsList.innerHTML = html;
                // Re-bind toggle details buttons after update
                bindToggleButtons();
            })
            .catch(err => {
                console.error('Error:', err);
                doctorsList.innerHTML = '<div class="col-12"><div class="alert alert-danger text-center">Error loading doctors. Please try again.</div></div>';
            });
        }

        // Debounce function to prevent rapid firing of events
        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }

        // Event listeners with debounce for search input
        searchInput.addEventListener('input', debounce(fetchDoctors));
        specializationFilter.addEventListener('change', fetchDoctors);
        cityFilter.addEventListener('change', fetchDoctors);

        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.value = '';
            specializationFilter.selectedIndex = 0;
            cityFilter.selectedIndex = 0;
            fetchDoctors();
        });
    });
    </script>
</body>
</html>