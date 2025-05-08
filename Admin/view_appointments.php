<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medinova</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
          
          .container-box {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-top: 30px;
    }
    .btn-custom {
      background-color: #007bff;
      color: white;
    }
  
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
                <a href="view_doctors.php"><i class="fas fa-users me-2"></i>View Doctors</a>
                <a href="view_appointments.php" class="active"><i class="fas fa-calendar-check me-2"></i>Appointments</a>
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
  
</head>
<body style="background-color:#eef4f8;">

<div class="container">
  <div class="container-box">
    <h4 class="mb-4">Add New Appointment</h4>
    <form>
      <div class="form-row">
        <div class="form-group col-md-4">
          <input type="text" class="form-control" placeholder="Patient Name">
        </div>
        <div class="form-group col-md-4">
          <input type="text" class="form-control" placeholder="Doctor Name">
        </div>
        <div class="form-group col-md-4">
          <input type="text" class="form-control" placeholder="City">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <input type="text" class="form-control" placeholder="Appointment Date (e.g., 2025-05-09)">
        </div>
        <div class="form-group col-md-4">
          <input type="text" class="form-control" placeholder="Time (e.g., 10:30 AM)">
        </div>
        <div class="form-group col-md-4">
          <input type="text" class="form-control" placeholder="Contact Number">
        </div>
      </div>

      <button type="submit" class="btn btn-custom">+ Add Appointment</button>
    </form>
  </div>

  <div class="container-box mt-5">
    <h4 class="mb-4">All Appointments</h4>
    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th>#ID</th>
          <th>Patient</th>
          <th>Doctor</th>
          <th>City</th>
          <th>Date</th>
          <th>Time</th>
          <th>Contact</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- Example Appointment Row -->
        <tr>
          <td>1</td>
          <td>Ali Raza</td>
          <td>Dr. Adeel</td>
          <td>Nazimabad</td>
          <td>2025-05-10</td>
          <td>11:00 AM</td>
          <td>03123456789</td>
          <td><span class="badge badge-success">Confirmed</span></td>
          <td><button class="btn btn-danger btn-sm">Delete</button></td>
        </tr>
        <!-- Add more rows dynamically here -->
      </tbody>
    </table>
  </div>
</div>

</body>
</html>