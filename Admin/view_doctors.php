<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medinova</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #13C5DD;
            --secondary: #354F8E;
            --light: #EFF5F9;
            --dark: #1D2A4D;
        }
        body {
            background-color: var(--light);
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e0e6ed;
        }
        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 600;
        }
        .topbar {
            background-color: var(--primary);
            padding: 15px 20px;
            border-bottom: 1px solid #e0e6ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #ffffff;
        }
        .dashboard-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            background-color: #ffffff;
        }
        .card h4 {
            font-size: 1.2rem;
            color: var(--primary);
        }
        .card p {
            color: var(--dark);
        }
        .brand-title {
            color: var(--primary);
            font-size: 1.25rem;
            font-weight: bold;
            padding: 20px 0;
            text-align: center;
        }
    .doctor-card {
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
    }
    .doctor-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
    }
  
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky">
                <h1 class="m-0 text-uppercase text-center py-4" style="color: var(--primary);">
                    <i class="fa fa-clinic-medical me-2"></i>Medinova
                </h1>

                <a href="dashboard.php" ><i class="fas fa-chart-line me-2"></i>Dashboard</a>
                <a href="add_city.php"><i class="fas fa-city me-2"></i>Add City</a>
                <a href="view_cities.php"><i class="fas fa-eye me-2"></i>View Cities</a>
                <a href="add_doctor.php"><i class="fas fa-user-md me-2"></i>Add Doctor</a>
                <a href="view_doctors.php" class="active"><i class="fas fa-users me-2"></i>View Doctors</a>
                <a href="view_appointments.php"><i class="fas fa-calendar-check me-2"></i>View Appointments</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </nav>
         <!-- Main -->
         <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="topbar">
                <div class="dashboard-title">Admin Dashboard</div>
                <div>
                    <img src="img/user.png" width="40" class="rounded-circle" alt="Admin">
                </div>
            </div>
            
<div class="container mt-4">
  <h2 class="mb-4">All Registered Doctors</h2>

  <div class="row">

    <!-- Doctor Card Start -->
    <div class="col-md-4">
      <div class="doctor-card bg-light text-center">
        <img src="\img\about.jpg" alt="Doctor Photo" class="doctor-img mb-3">
        <h5>Muhammad Bin Adeel</h5>
        <p><strong>Specialization:</strong> fdg</p>
        <p><strong>Hospital:</strong> kkk</p>
        <p><strong>City:</strong> Nazimabad</p>
        <p><strong>Timing:</strong> sth</p>
        <p><strong>Days:</strong> areg</p>
        <p><strong>Experience:</strong> agr</p>
        <p><strong>Phone:</strong> 03331114816</p>
        <p><strong>Description:</strong> awertfEWF</p>
        <span class="badge badge-success">Approved</span>
      </div>
    </div>
    <!-- Doctor Card End -->

    <!-- Add more doctor cards in similar format -->

  </div>
</div>

</body>
</html>
