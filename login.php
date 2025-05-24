<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("db.php");

$error = ""; // Initialize error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for admin (hardcoded)
    if ($email === 'admin' && $password === 'admin123') {
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

       if ($doctor['password'] == $password) { // Plain text match
    $_SESSION['role'] = 'doctor';
    $_SESSION['doctor_email'] = $email;
    $_SESSION['doctor_id'] = $doctor['id']; // ✅ Add this line

    // Redirect to doctor profile with doctor ID
    $doctor_id = $doctor['id'];
    header("Location: doctor_profile.php?id=$doctor_id");
    exit();
}
    }

    // Patient login
    $query = "SELECT * FROM patients WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['role'] = 'patient';
        $_SESSION['email'] = $email;
        $_SESSION['patient_id'] = mysqli_fetch_assoc($result)['id'];
        header("Location: appointment.php");
        exit();
    }

    $error = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
    :root {
        --primary: #13C5DD;
        --secondary: #354F8E;
        --light: #EFF5F9;
        --dark: #1D2A4D;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    /* Background Image + Gradient Overlay */
    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-image: linear-gradient(to right, rgba(19, 197, 221, 0.7), rgba(53, 79, 142, 0.7)), 
                          url('https://images.unsplash.com/photo-1588776814546-2d7733c14ecb?auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        animation: backgroundMove 20s infinite alternate;
        z-index: -1;
    }

    @keyframes backgroundMove {
        0% { background-position: center top; }
        100% { background-position: center bottom; }
    }

    .login-box {
        background-color: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 400px;
        animation: fadeIn 1s ease-out;
        z-index: 2;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-box h2 {
        text-align: center;
        color: var(--dark);
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        color: var(--dark);
        display: block;
        margin-top: 15px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-top: 8px;
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: border 0.3s ease;
    }

    input:focus {
        border-color: var(--primary);
        outline: none;
    }

    input[type="submit"] {
        background-color: var(--primary);
        color: white;
        padding: 12px;
        margin-top: 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: var(--secondary);
    }

    .error {
        color: red;
        text-align: center;
        margin-top: 10px;
    }

    .signup {
        text-align: center;
        margin-top: 15px;
    }

    .signup a {
        color: var(--primary);
        text-decoration: none;
    }

    .signup a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-box {
            padding: 30px 20px;
        }
    }
</style>
    <title>Login</title>
</head>

<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Email:</label>
            <input type="text" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Login">
        </form>

        <div class="signup">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
    </div>
</body>
</html>