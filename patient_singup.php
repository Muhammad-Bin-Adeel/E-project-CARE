<?php
session_start();
include("db.php");

 $conn->query("CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    gender ENUM('Male', 'Female', 'Other'),
    age INT,
    address TEXT,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
// Form submit handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $conn->real_escape_string($_POST["name"]);
    $email    = $conn->real_escape_string($_POST["email"]);
    $phone    = $conn->real_escape_string($_POST["phone"]);
    $gender   = $conn->real_escape_string($_POST["gender"]);
    $age      = intval($_POST["age"]);
    $address  = $conn->real_escape_string($_POST["address"]);
    $password = $conn->real_escape_string($_POST["password"]);

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Name, Email & Password are required.";
    } else {
        // Email already exists?
        $check = $conn->query("SELECT id FROM patients WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email already registered.";
        } else {
        $conn->query("INSERT INTO patients (name, email, phone, gender, age, address, password) 
              VALUES ('$name', '$email', '$phone', '$gender', $age, '$address', '$password')");
             $_SESSION["success"] = "Signup successful! You can now log in.";
             header("Location: loginn.php");
             exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Sign up</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0px 0px 10px #ccc;
        }

        .signup-form h2 {
            margin-bottom: 20px;
            color: #0d6efd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: 600;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0b5ed7;
        }

        .message {
            padding: 10px;
            background: #f8d7da;
            color: #842029;
            margin-bottom: 15px;
            border-radius: 5px;
        }

    </style>
</head>
<body>

<div class="signup-form">
    <h2>Patient Signup</h2>

    <?php if (isset($error)): ?>
        <div class="message"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required />
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required />
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" />
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender">
                <option value="">-- Select --</option>
                <option>Male</option>
                <option>Female</option>
                <option>other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Age</label>
            <input type="number" name="age" min="0" />
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address"></textarea>
        </div>

        <div class="form-group">
            <label>Create Password</label>
            <input type="password" name="password" required />
        </div>

        <button type="submit" class="btn">Signup</button>
    </form>
</div>

</body>
</html>
