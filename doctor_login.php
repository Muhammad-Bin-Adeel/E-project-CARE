<?php
session_start();
include("db.php");



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // no escaping needed here

    $res = $conn->query("SELECT * FROM doctors WHERE email='$email' AND status='approved'");

    if ($res->num_rows === 1) {
        $doctor = $res->fetch_assoc();

        // Compare input password with hashed password from DB
        if (password_verify($password, $doctor['password'])) {
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_name'] = $doctor['name'];
            $_SESSION['doctor_email'] = $doctor['email'];

            header("Location: doctor_dashboard.php");
            exit;
        } else {
            $error = "Invalid Email or Password!";
        }
    } else {
        $error = "Doctor not found or not approved!";
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
    <h2>Doctor Login</h2>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
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
