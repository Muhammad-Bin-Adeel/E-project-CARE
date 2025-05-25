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
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <img src="<?= htmlspecialchars($row['image']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                        <h6 class="text-primary"><?= htmlspecialchars($row['specialization']) ?></h6>
                        <p><strong>City:</strong> <?= htmlspecialchars($row['city']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                        <p><strong>Experience:</strong> <?= htmlspecialchars($row['experience']) ?></p>

                        <div class="collapse" id="doctorDetails<?= $counter ?>">
                            <p><strong>Hospital:</strong> <?= htmlspecialchars($row['hospital_name']) ?></p>
                            <p><strong>Days:</strong> <?= htmlspecialchars($row['days']) ?></p>
                            <p><strong>Timing:</strong> <?= htmlspecialchars($row['timing']) ?></p>
                            <p><strong>Degree:</strong> <?= htmlspecialchars($row['degree']) ?></p>
                            <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                            <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <button class="btn btn-sm btn-outline-primary toggle-details-btn flex-grow-1" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#doctorDetails<?= $counter ?>" 
                                aria-expanded="false" 
                                aria-controls="doctorDetails<?= $counter ?>">
                                More Details
                            </button>
                            
                            <a href="appointment.php?doctor_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary flex-grow-1">
                                Appointment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $counter++;
        }
    } else {
        echo '<div class="col-12"><p class="text-center text-muted">No approved doctors found for selected filter.</p></div>';
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
    <title>CARE - Hospital .</title>
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
        .join-doctor-btn {
            padding: 8px 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            border-radius: 30px;
            background-color: #0d6efd;
            color: white;
            transition: all 0.3s ease;
        }
        .join-doctor-btn:hover {
            background-color: #0b5ed7;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        /* Filter form styles */
        #filterForm {
            max-width: 900px;
            margin: 30px auto;
        }
        
        /* Doctor Cards Styles */
        .doctor-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background: #fff;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .doctor-card .card-img-top {
            transition: transform 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 250px;
            object-fit: cover;
            object-position: top;
        }

        .doctor-card:hover .card-img-top {
            transform: scale(1.03);
        }

        .doctor-card .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            height: calc(100% - 250px);
        }

        .doctor-card .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .doctor-card .text-primary {
            color: #3498db !important;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .doctor-card p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .doctor-card p strong {
            color: #34495e;
            font-weight: 600;
        }

        .doctor-card .btn-group {
            margin-top: auto;
            padding-top: 1rem;
            display: flex;
            gap: 10px;
        }

        .doctor-card .toggle-details-btn {
            border-radius: 5px;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            flex: 1;
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .doctor-card {
                margin-bottom: 20px;
            }
            
            .doctor-card .card-img-top {
                height: 200px;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .doctor-card .card-img-top {
                height: 220px;
            }
        }
    </style>
</head>

<body>
    <!-- Topbar and Navbar sections remain the same as your original code -->
    
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

            <!-- Doctors List Container -->
            <div class="row mt-5" id="doctorsList">
                <?php if ($result->num_rows > 0): ?>
                    <?php $counter = 1; while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm h-100">
                                <img src="<?= htmlspecialchars($row['image']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= htmlspecialchars($row['name']) ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                    <h6 class="text-primary"><?= htmlspecialchars($row['specialization']) ?></h6>
                                    <p><strong>City:</strong> <?= htmlspecialchars($row['city']) ?></p>
                                    <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                                    <p><strong>Experience:</strong> <?= htmlspecialchars($row['experience']) ?></p>

                                    <div class="collapse" id="doctorDetails<?= $counter ?>">
                                        <p><strong>Hospital:</strong> <?= htmlspecialchars($row['hospital_name']) ?></p>
                                        <p><strong>Days:</strong> <?= htmlspecialchars($row['days']) ?></p>
                                        <p><strong>Timing:</strong> <?= htmlspecialchars($row['timing']) ?></p>
                                        <p><strong>Degree:</strong> <?= htmlspecialchars($row['degree']) ?></p>
                                        <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                                        <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                                        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                                    </div>

                                    <div class="d-flex gap-2 mt-auto">
                                        <button class="btn btn-sm btn-outline-primary toggle-details-btn flex-grow-1" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#doctorDetails<?= $counter ?>" 
                                            aria-expanded="false" 
                                            aria-controls="doctorDetails<?= $counter ?>">
                                            More Details
                                        </button>
                                        
                                        <a href="appointment.php?doctor_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary flex-grow-1">
                                            Appointment
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center text-muted">No approved doctors found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Team End -->

    <!-- Footer section remains the same as your original code -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle button text for details
        function bindToggleButtons() {
            const buttons = document.querySelectorAll('.toggle-details-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = btn.getAttribute('data-bs-target');
                    const collapseEl = document.querySelector(targetId);
                    
                    // Use Bootstrap's Collapse directly
                    const bsCollapse = new bootstrap.Collapse(collapseEl, {
                        toggle: true
                    });
                    
                    setTimeout(() => {
                        if (collapseEl.classList.contains('show')) {
                            btn.textContent = 'Less Details';
                            btn.classList.remove('btn-outline-primary');
                            btn.classList.add('btn-outline-danger');
                        } else {
                            btn.textContent = 'More Details';
                            btn.classList.remove('btn-outline-danger');
                            btn.classList.add('btn-outline-primary');
                        }
                    }, 300);
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
                doctorsList.innerHTML = '<div class="col-12"><p class="text-center text-danger">Error loading doctors. Please try again.</p></div>';
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