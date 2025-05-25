<?php
session_start();
include("db.php");

// Get ID from URL
if (!isset($_GET['id'])) {
    echo "No doctor ID provided.";
    exit;
}

// Check login
if (!isset($_SESSION['doctor_id'])) {
    echo "<div class='alert'>Please log in to view your profile.</div>";
    exit;
}

$doctor_id = intval($_SESSION['doctor_id']);

// Fetch approved doctor
$query = "SELECT * FROM doctors WHERE id = $doctor_id AND status = 'approved'";
$result = $conn->query($query);



if ($result->num_rows === 0) {
    echo "Doctor not found.";
    exit;
}

$doctor = $result->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $hospital_name = $_POST['hospital_name'];
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $degree = $_POST['degree'] === 'Other' ? $_POST['other_degree'] : $_POST['degree'];
    $city = $_POST['city'];
    $days = implode(",", $_POST['days']);
    $timing = implode(",", $_POST['timing']);
    $experience = $_POST['experience'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $location = $_POST['location'];

    // Handle image if uploaded
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, "uploads/" . $image_name);
        $image_sql = ", image = '$image_name'";
    } else {
        $image_sql = "";
    }

    $update_sql = "UPDATE doctors SET 
        name = '$name',
        hospital_name = '$hospital_name',
        phone = '$phone',
        specialization = '$specialization',
        degree = '$degree',
        city = '$city',
        days = '$days',
        timing = '$timing',
        experience = '$experience',
        description = '$description',
        address = '$address',
        email = '$email',
        password = '$password',
        location = '$location'
        $image_sql
        WHERE id = $id";

    if ($conn->query($update_sql)) {
        header("Location: modefied_doctor.php");
        exit;
    } else {
        echo "Error updating doctor: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
     :root {
            --primary: #13C5DD;
            --secondary: #354F8E;
            --light: #EFF5F9;
            --dark: #1D2A4D;
            --success: #28a745;
            --danger: #dc3545;
        }
        
        body {
            background-color: var(--light);
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            height: 100vh;
    overflow-y: auto; /* ✅ Enable vertical scroll */
    background-color: #ffffff;
    border-right: 1px solid #e0e6ed;
    position: fixed;
    width: 250px;
    transition: all 0.3s;
    z-index: 1000;
        }
        
        .brand-title {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }
        
        .sidebar-menu {
            padding: 0 15px;
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            color: var(--secondary);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 5px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .nav-link {
            color: var(--dark);
            border-radius: 5px;
            margin-bottom: 5px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: var(--primary);
            color: white !important;
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }
        
        .topbar {
            background-color: var(--primary);
            padding: 12px 20px;
            border-bottom: 1px solid #e0e6ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            color: white;
        }
        
        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
        
        .user-menu img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Search Bar */
         .search-bar {
            position: relative;
            width: 220px;
            margin: 0 15px;
        }
        
        .search-bar input {
            background-color: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 6px 12px 6px 35px;
            border-radius: 20px;
            width: 100%;
            font-size: 0.9rem;
        }
        
        .search-bar input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        
        .search-bar i {
            position: absolute;
            left: 12px;
            top: 8px;
            font-size: 0.9rem;
        }
        
        /* Notification Bell */
        .notification-bell {
            position: relative;
            margin-right: 15px;
            font-size: 1.1rem;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Content Area */
       
        .card {
  border-radius: 10px;
  border: 1px solid #ddd;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background-color: var(--light);
  font-family: "Segoe UI", sans-serif;
  color: var(--dark);
  
}

.text-primary {
  color: var(--primary);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-weight: 600;
  margin-bottom: 5px;
}

.form-control {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 15px;
}

.form-actions {
  text-align: right;
}

.form-actions button {
  background-color: var(--primary);
  border: none;
  padding: 10px 25px;
  border-radius: 6px;
  color: #fff;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.form-actions button:hover {
  background-color: var(--secondary);
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .form-actions {
    text-align: center;
  }
}
    
/* Alert Box */
.alert {
    padding: 20px;
    background-color: var(--primary);
    color: white;
    border-radius: 5px;
    margin: 20px 0;
}

.alert button {
    background: none;
    color: white;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
}
        
        
        
        
        /* Dropdown Animation */
        .collapse:not(.show) {
            display: none;
        }
        
        .collapsing {
            height: 0;
            overflow: hidden;
            transition: height 0.35s ease;
        }
        
        /* Responsive Styles */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .search-bar {
                width: 180px;
            }
        }
        
        @media (max-width: 767.98px) {
            .search-bar {
                display: none;
            }
            .notification-bell {
                margin-right: 10px;
            }
        }

</style>
</head>
<body>
     <nav class="sidebar">
        <div class="brand-title">
            <i class="fas fa-clinic-medical me-2"></i>CARE
        </div>
        
        <div class="sidebar-menu">
            <!-- Dashboard Section -->
            <div class="sidebar-section">
                <div class="section-title">Dashboard</div>
                <a href="doctor_profile.php" class="nav-link">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- My Panel Section -->
            <div class="sidebar-section">
                <div class="section-title">My Panel</div>
                
                <!-- My Patients -->
                <div class="nav-link" onclick="toggleCollapse('patientsCollapse')">
                    <i class="fas fa-procedures"></i>
                    <span>Patients</span>
                    <i class="fas fa-angle-down ms-auto" id="patientsCollapseIcon"></i>
                </div>
                <div class="collapse" id="patientsCollapse">
                    <a href="view_patients.php" class="nav-link ps-4">
                        <i class="fas fa-list"></i>
                        <span>View Patients</span>
                    </a>
                    <a href="modefied_doctor.php" class="nav-link ps-4">
                        <i class="fas fa-edit"></i>
                        <span>Modify Patients</span>
                    </a>
                </div>
                
                <!-- My Appointments -->
                <a href="doctor_appointment.php" class="nav-link ">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </div>
           
            
            <!-- Account Section -->
            <div class="sidebar-section">
                <div class="section-title">Account</div>
                <a href="modefied_doctor.php" class="nav-link active">
                    <i class="fas fa-user-cog"></i>
                    <span>Profile Settings</span>
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>
     <!-- Content Area -->
        <div class="content-wrapper">
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow p-4">
        <h4 class="mb-4 text-primary">
          <i class="fas fa-user-md me-2"></i> <?= isset($doctor) ? 'Edit Doctor' : 'Add New Doctor' ?>
        </h4>

        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $doctor['id'] ?? '' ?>">

          <div class="form-grid">
    <div class="form-group">
      <label class="form-label">Name:</label>
      <input type="text" name="name" class="form-control" value="<?= $doctor['name'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Hospital Name:</label>
      <input type="text" name="hospital_name" class="form-control" value="<?= $doctor['hospital_name'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Phone:</label>
      <input type="text" name="phone" class="form-control" value="<?= $doctor['phone'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Specialization:</label>
      <input type="text" name="specialization" class="form-control" value="<?= $doctor['specialization'] ?? '' ?>" required>
    </div>

        <div class="form-group">
    <label for="degree">Degree</label>
    <select name="degree" id="degree" class="form-control" required onchange="toggleOtherDegree(this)">
        <option value="">-- Select Degree --</option>
        <option value="MBBS" <?= isset($doctor['degree']) && $doctor['degree'] == 'MBBS' ? 'selected' : '' ?>>MBBS</option>
        <option value="MD" <?= isset($doctor['degree']) && $doctor['degree'] == 'MD' ? 'selected' : '' ?>>MD (Doctor of Medicine)</option>
        <option value="MS" <?= isset($doctor['degree']) && $doctor['degree'] == 'MS' ? 'selected' : '' ?>>MS (Master of Surgery)</option>
        <option value="BDS" <?= isset($doctor['degree']) && $doctor['degree'] == 'BDS' ? 'selected' : '' ?>>BDS (Dental)</option>
        <option value="MDS" <?= isset($doctor['degree']) && $doctor['degree'] == 'MDS' ? 'selected' : '' ?>>MDS (Dental Surgery)</option>
        <option value="BHMS" <?= isset($doctor['degree']) && $doctor['degree'] == 'BHMS' ? 'selected' : '' ?>>BHMS (Homeopathy)</option>
        <option value="BAMS" <?= isset($doctor['degree']) && $doctor['degree'] == 'BAMS' ? 'selected' : '' ?>>BAMS (Ayurveda)</option>
        <option value="DNB" <?= isset($doctor['degree']) && $doctor['degree'] == 'DNB' ? 'selected' : '' ?>>DNB</option>
        <option value="PhD" <?= isset($doctor['degree']) && $doctor['degree'] == 'PhD' ? 'selected' : '' ?>>PhD</option>
        <option value="Other" <?= isset($doctor['degree']) && !in_array($doctor['degree'], ['MBBS','MD','MS','BDS','MDS','BHMS','BAMS','DNB','PhD']) ? 'selected' : '' ?>>Other</option>
    </select>
</div>

<div class="form-group" id="other-degree-group" style="display: none;">
    <label for="other_degree">Please specify</label>
    <input type="text" name="other_degree" id="other_degree" class="form-control"
           value="<?= (!in_array($doctor['degree'] ?? '', ['MBBS','MD','MS','BDS','MDS','BHMS','BAMS','DNB','PhD']) && isset($doctor['degree'])) ? $doctor['degree'] : '' ?>">
</div>
    
    <div class="form-group">
  <label class="form-label">City:</label>
  <select name="city" class="form-control" required>
    <option value="">-- Select City --</option>
    <?php
      include 'db.php'; // your DB connection file
      $city_query = mysqli_query($conn, "SELECT * FROM city");
      while ($row = mysqli_fetch_assoc($city_query)) {
        $selected = (isset($doctor['city']) && $doctor['city'] == $row['city_name']) ? 'selected' : '';
        echo "<option value='{$row['city_name']}' $selected>{$row['city_name']}</option>";
      }
    ?>
  </select>
</div>
    

    <div class="form-group">
  <label class="form-label">Available Days:</label><br>
  <?php
    $selectedDays = isset($doctor['days']) ? explode(',', $doctor['days']) : [];
    $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($allDays as $day) {
        $checked = in_array($day, $selectedDays) ? 'checked' : '';
        echo "<label><input type='checkbox' name='days[]' value='$day' $checked> $day</label><br>";
    }
  ?>
</div>

    <div class="form-group">
  <label class="form-label">Timing:</label>
  <select name="timing[]" class="form-control" multiple required>
    <?php
      $available_timings = [
          "09:00 AM - 11:00 AM",
          "11:00 AM - 01:00 PM",
          "02:00 PM - 04:00 PM",
          "04:00 PM - 06:00 PM"
      ];
      $selectedTimings = isset($doctor['timing']) ? explode(",", $doctor['timing']) : [];
      foreach ($available_timings as $time) {
          $selected = in_array($time, $selectedTimings) ? 'selected' : '';
          echo "<option value=\"$time\" $selected>$time</option>";
      }
    ?>
  </select>
  <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
</div>

    <div class="form-group">
      <label class="form-label">Experience:</label>
      <input type="text" name="experience" class="form-control" value="<?= $doctor['experience'] ?? '' ?>" required>
    </div>

    <div class="form-group full-width">
      <label class="form-label">Description:</label>
      <textarea name="description" class="form-control"><?= $doctor['description'] ?? '' ?></textarea>
    </div>

    <div class="form-group">
    <label for="address">Full Address</label>
    <textarea name="address" id="address" class="form-control"><?= $doctor['address'] ?? '' ?></textarea>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= $doctor['email'] ?? '' ?>" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" value="<?= isset($doctor) ? $doctor['password'] : '' ?>" required/>
</div>

   
    <div class="form-group full-width">
              <label class="form-label">Location (Google Maps)</label>
              <input type="text" name="location" class="form-control" value="<?= $doctor['location'] ?? '' ?>" required>
            </div>

            <div class="form-group full-width">
              <label class="form-label">Image</label>
              <input type="file" name="image" class="form-control">
            </div>
          </div>

          <div class="form-actions text-end mt-4">
            <button type="submit" class="btn btn-primary px-4">Save Doctor</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle sidebar on mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        if (window.innerWidth < 992 && 
            !sidebar.contains(event.target) && 
            !toggleBtn.contains(event.target) && 
            sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    
    // Custom dropdown toggle function
    function toggleCollapse(id) {
        const element = document.getElementById(id);
        const icon = document.getElementById(id + 'Icon');
        
        if (element.classList.contains('show')) {
            element.classList.remove('show');
            icon.classList.remove('fa-angle-up');
            icon.classList.add('fa-angle-down');
        } else {
            // Close all other dropdowns first
            document.querySelectorAll('.collapse.show').forEach(collapse => {
                if (collapse.id !== id) {
                    collapse.classList.remove('show');
                    const otherIcon = document.getElementById(collapse.id + 'Icon');
                    if (otherIcon) {
                        otherIcon.classList.remove('fa-angle-up');
                        otherIcon.classList.add('fa-angle-down');
                    }
                }
            });
            
            element.classList.add('show');
            icon.classList.remove('fa-angle-down');
            icon.classList.add('fa-angle-up');
        }
    }
    
    // Initialize dropdowns based on current page
    document.addEventListener('DOMContentLoaded', function() {
        // Get current page URL
        const currentUrl = window.location.pathname.split('/').pop();
        
        // Remove active class from all links first
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Map of pages to their parent dropdown IDs
        const pageToDropdownMap = {
            'dashboard.php': null,
            'add_city.php': 'citiesCollapse',
            'view_cities.php': 'citiesCollapse',
            'add_doctor.php': 'doctorsCollapse',
            'view_doctors.php': 'doctorsCollapse',
            'manage_doctors.php': 'doctorsCollapse',
            'view_patients.php': 'patientsCollapse',
            'manage_patients.php': 'patientsCollapse',
            'view_appointments.php': null,
            'manage_users.php': null,
            'diseases_info.php': 'contentCollapse',
            'medical_news.php': 'contentCollapse',
            'profile.php': null,
            'settings.php': null
        };
        
        // Check if current page is in our map
        if (pageToDropdownMap[currentUrl]) {
            const dropdownId = pageToDropdownMap[currentUrl];
            const element = document.getElementById(dropdownId);
            const icon = document.getElementById(dropdownId + 'Icon');
            
            if (element && !element.classList.contains('show')) {
                element.classList.add('show');
                if (icon) {
                    icon.classList.remove('fa-angle-down');
                    icon.classList.add('fa-angle-up');
                }
            }
        }
        
        // Highlight active link
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentUrl) {
                link.classList.add('active');
                
                // If this is a child link, also highlight its parent dropdown
                const parentDropdown = link.closest('.collapse');
                if (parentDropdown) {
                    const parentLink = document.querySelector(`[onclick*="${parentDropdown.id}"]`);
                    if (parentLink) {
                        parentLink.classList.add('active');
                    }
                }
            }
        });
        
        // Special case for dashboard (default page)
        if (currentUrl === '' || currentUrl === 'index.php') {
            document.querySelector('a[href="admin_dashboard.php"]').classList.add('active');
        }
    });
    function initAutocomplete() {
        const input = document.getElementById('location-input');
        new google.maps.places.Autocomplete(input);
    }
    google.maps.event.addDomListener(window, 'load', initAutocomplete);
    
</script>
<script>
function toggleOtherDegree(select) {
    const otherField = document.getElementById('other-degree-group');
    if (select.value === 'Other') {
        otherField.style.display = 'block';
        document.getElementById('other_degree').setAttribute('required', 'required');
    } else {
        otherField.style.display = 'none';
        document.getElementById('other_degree').removeAttribute('required');
    }
}

// Initialize on page load in case "Other" is already selected
window.onload = function () {
    const degreeSelect = document.getElementById('degree');
    toggleOtherDegree(degreeSelect);
};
</script>
</body>
</html>