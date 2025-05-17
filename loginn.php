<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for admin (hardcoded)
    if ($email === 'admin@example.com' && $password === 'admin123') {
        $_SESSION['role'] = 'admin';
        $_SESSION['email'] = $email;
        header("Location: admin_dashboard.php");
        exit();
    }

   // Check in doctors table
$query = "SELECT * FROM doctors WHERE email='$email' AND status='approved'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $doctor = mysqli_fetch_assoc($result);

    if ($doctor['password'] == $password) {  // Simple match (if plain text)
        $_SESSION['role'] = 'doctor';
        $_SESSION['doctor_email'] = $email;  // ✅ Corrected this line
        header("Location: doctor_dashboard.php");
        exit();
    }
}
    // Check in patients table
    $query = "SELECT * FROM patients WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
        $_SESSION['role'] = 'patient';
        $_SESSION['email'] = $email;
        header("Location: appointment.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Login</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 50px; }
        .login-box {
            background: white;
            width: 400px;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
   
    <form method="POST" action="">
        <label>Email:</label>
        <input type="text" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
