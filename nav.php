<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("db.php");

// Get current page like: index.php, about.php, etc.
$currentPage = basename($_SERVER['PHP_SELF']);

// Avatar data
$nameOrEmail = '';
if (isset($_SESSION['patient_name']) && strlen(trim($_SESSION['patient_name'])) > 0) {
    $nameOrEmail = trim($_SESSION['patient_name']);
} elseif (isset($_SESSION['patient_email']) && strlen(trim($_SESSION['patient_email'])) > 0) {
    $nameOrEmail = trim($_SESSION['patient_email']);
} else {
    $nameOrEmail = 'User';
}
$firstLetter = strtoupper(substr($nameOrEmail, 0, 1));
?>
<style>
    .dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}

.dropdown-menu > .dropdown-submenu > .dropdown-toggle::after {
  content: " \25B8";
  float: right;
}
        .join-doctor-btn {
    padding: 8px 20px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    border-radius: 30px;
    background-color: #0d6efd; /* Bootstrap primary */
    color: white;
    transition: all 0.3s ease;
}

.join-doctor-btn:hover {
    background-color: #0b5ed7;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

</style>
<!-- Navbar Start -->
<div class="container-fluid sticky-top bg-white shadow-sm">
  <div class="container">
    <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
      <a href="index.php" class="navbar-brand">
        <h1 class="m-0 text-uppercase text-primary">
          <i class="fa fa-clinic-medical me-2"></i>CARE
        </h1>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <!-- Navigation Links -->
        <div class="navbar-nav ms-auto py-0">
          <a href="index.php" class="nav-item nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a>
          <a href="about.php" class="nav-item nav-link <?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">About</a>
          <a href="doctors.php" class="nav-item nav-link <?php echo ($currentPage == 'doctors.php') ? 'active' : ''; ?>">Doctors</a>
          <a href="appointment.php" class="nav-item nav-link <?php echo ($currentPage == 'appointment.php') ? 'active' : ''; ?>">Appointment</a>

          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle <?php echo ($currentPage == 'blog.php' || $currentPage == 'Disease.php') ? 'active' : ''; ?>" data-bs-toggle="dropdown">Medical Info</a>
            <div class="dropdown-menu m-0">
              <a href="blog.php" class="dropdown-item <?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>">Medical News</a>
              <a href="Disease.php" class="dropdown-item <?php echo ($currentPage == 'Disease.php') ? 'active' : ''; ?>">Disease Info</a>
            </div>
          </div>

          <a href="contact.php" class="nav-item nav-link <?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>">Contact</a>
        </div>

        <!-- Join as Doctor -->
        <div class="ms-3">
          <a href="doctorform.php" class="btn btn-primary join-doctor-btn">Join As Doctor</a>
        </div>

        <!-- Login / Avatar -->
        <?php if (isset($_SESSION['patient_id'])): ?>
          <div class="d-flex align-items-center ms-3">
            <div class="dropdown">
              <button class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
                      type="button"
                      id="userDropdown"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      title="<?php echo htmlspecialchars($nameOrEmail); ?>"
                      style="width: 40px; height: 40px; font-size: 18px; padding: 0;">
                <?php echo htmlspecialchars($firstLetter); ?>
              </button>

              <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3 overflow-hidden" aria-labelledby="userDropdown" style="min-width: 220px;">
                <li class="px-3 py-2 bg-light border-bottom">
                  <small class="text-muted">Welcome</small><br>
                  <strong class="text-dark"><?php echo htmlspecialchars($nameOrEmail); ?></strong>
                </li>
                
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="my_appointments.php">
                  <i class="fas fa-calendar-check text-success"></i> My Appointments
                </a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="logout.php">
                  <i class="fas fa-sign-out-alt text-danger"></i> Logout
                </a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="switch_account.php">
                  <i class="fas fa-user-plus text-secondary"></i> Login Another Account
                </a></li>
              </ul>
            </div>
          </div>
        <?php else: ?>
          <div class="button-container ms-3">
            <a href="login.php" class="btn btn-outline-secondary btn-sm">Login</a>
          </div>
        <?php endif; ?>
      </div>
    </nav>
  </div>
</div>
<!-- Navbar End -->
