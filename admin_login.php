<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "admin123") {
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Medinova</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Medinova CSS & Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Admin Login Styling -->
    <style>
    body, html {
        height: 100%;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .admin-login-container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #dff6ff, #e9faff);
        position: relative;
    }

    .admin-login-box {
        position: relative;
        background-color: #ffffff;
        padding: 50px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 450px;
        text-align: center;
        z-index: 1;
    }

    .admin-login-box h2 {
        color: #0dcaf0;
        font-weight: 700;
        margin-bottom: 30px;
    }

    .form-control {
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        font-size: 1.1rem;
        background-color: #f0faff;
        border: 1px solid #d3eefd;
        color: #333;
    }

    .form-control::placeholder {
        color: #7ca6c9;
    }

    .btn-primary {
        background-color: #0dcaf0;
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-size: 1.1rem;
        font-weight: bold;
        color: #fff;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0bb5d6;
    }

    @media (max-width: 576px) {
        .admin-login-box {
            padding: 35px 25px;
        }
    }
    </style>
</head>
<body>

<div class="admin-login-container">
    <div class="admin-login-box">
        <h2>Admin Login</h2>
        <form method="post" action="">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <?php if (isset($error)) echo "<p class='text-danger mt-3' style='color:red;'>$error</p>"; ?>
        </form>
    </div>
</div>

<!-- JS Libraries -->
<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>




