<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #f0f6ff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navbar */
    .navbar {
      width: 100%;
      background: #fff;
      padding: 15px 30px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .navbar ul li a {
      text-decoration: none;
      color: #000;
      font-weight: 500;
    }

    .join-btn {
      background-color: #007bff;
      color: #fff;
      padding: 10px 15px;
      border-radius: 8px;
      font-weight: 600;
      border: none;
      margin-right: 10px;
      cursor: pointer;
    }

    .user-icon {
      width: 40px;
      height: 40px;
      background-color: #00bcd4;
      border-radius: 50%;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      cursor: pointer;
      position: relative;
    }

    .dropdown {
      position: absolute;
      top: 60px;
      right: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      width: 220px;
      display: none;
      z-index: 100;
    }

    .dropdown.show {
      display: block;
    }

    .dropdown p {
      padding: 12px;
      font-weight: 600;
    }

    .dropdown ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .dropdown ul li {
      border-top: 1px solid #eee;
    }

    .dropdown ul li a {
      display: block;
      padding: 12px;
      text-decoration: none;
      color: #333;
    }

    .dropdown ul li a:hover {
      background-color: #f5f5f5;
    }

    /* Profile Card */
    .container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .profile-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .profile-card img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
    }

    .profile-card h2 {
      margin: 10px 0 5px;
      font-size: 22px;
    }

    .profile-card p {
      margin: 4px 0;
      color: #555;
    }

    .btn {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      font-weight: 600;
      border: 2px solid #007bff;
      background-color: white;
      color: #007bff;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background-color: #007bff;
      color: white;
    }

    .btn.logout {
      background-color: #007bff;
      color: white;
      border: none;
    }

    .btn.logout:hover {
      background-color: #0056b3;
    }

    /* Footer */
    footer {
      background-color: #007bff;
      color: white;
      padding: 50px 20px 20px;
      text-align: center;
    }

    .footer-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      max-width: 900px;
      margin: auto;
    }

    .footer-card {
      background-color: white;
      color: #333;
      border-radius: 15px;
      padding: 25px 20px;
      width: 100%;
      max-width: 600px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
      text-align: left;
    }

    .footer-card h3 {
      margin-bottom: 10px;
      color: #007bff;
    }

    .footer-card ul {
      list-style: none;
      padding: 0;
    }

    .footer-card ul li {
      margin-bottom: 8px;
      font-size: 14px;
    }

    .footer-card ul li a {
      color: #333;
      text-decoration: none;
    }

    .footer-card ul li a:hover {
      text-decoration: underline;
    }

    .footer-bottom {
      margin-top: 30px;
      font-size: 13px;
      color: #ddd;
    }

    @media (min-width: 768px) {
      .footer-container {
        flex-direction: row;
        justify-content: center;
      }

      .footer-card {
        max-width: 280px;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Doctors</a></li>
      <li><a href="#">Appointments</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
    <div style="display: flex; align-items: center;">
      
      <div class="user-icon" id="userBtn">E</div>
    </div>

    <!-- Dropdown -->
    <div class="dropdown" id="dropdownMenu">
      <p>Welcome<br><strong>ejazsab</strong></p>
      <ul>
        <li><a href="#">👤 Profile</a></li>
        <li><a href="#">📅 My Appointments</a></li>
        <li><a href="#">🔓 Logout</a></li>
        <li><a href="#">🔁 Login Another Account</a></li>
      </ul>
    </div>
  </div>

  <!-- Profile Card -->
  <div class="container">
    <div class="profile-card">
      <img src="https://i.ibb.co/2Y4Hz6W/avatar.jpg" alt="User Photo">
      <h2>John Doe</h2>
      <p>john.doe@example.com</p>
      <p>123-456-7890</p>
      <p>Male</p>

      <button class="btn">Edit Profile</button>
      <button class="btn">Change Password</button>
      <button class="btn logout">Logout</button>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="footer-container">

      <div class="footer-card">
        <h3>About Us</h3>
        <p style="font-size: 14px;">
          We provide trusted doctor appointment services and healthcare guidance with care and professionalism.
        </p>
      </div>

      <div class="footer-card">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#">🏠 Home</a></li>
          <li><a href="#">👨‍⚕️ Doctors</a></li>
          <li><a href="#">📅 Appointments</a></li>
          <li><a href="#">📞 Contact</a></li>
        </ul>
      </div>

      <div class="footer-card">
        <h3>Contact</h3>
        <ul>
          <li>📞 123-456-7890</li>
          <li>✉️ support@example.com</li>
          <li>📍 Karachi, Pakistan</li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      © 2025 YourClinic. All Rights Reserved.
    </div>
  </footer>

  <!-- Script -->
  <script>
    const userBtn = document.getElementById('userBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    userBtn.addEventListener('click', () => {
      dropdownMenu.classList.toggle('show');
    });

    window.addEventListener('click', function(e) {
      if (!userBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove('show');
      }
    });
  </script>

</body>
</html>
