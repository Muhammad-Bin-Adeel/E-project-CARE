<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "admin123") {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
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
            background: linear-gradient(135deg, #e0f2ff, #f0faff);
            background-size: cover;
            position: relative;
        }

        .admin-login-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #dbefff, #f4faff);
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        .admin-login-box {
            position: relative;
            z-index: 1;
            background-color: #fff;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .admin-login-box h2 {
            color: #0072ff;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: #0072ff;
            border-color: #0072ff;
            border-radius: 10px;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: bold;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #005fcc;
            border-color: #005fcc;
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




