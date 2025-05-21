<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Submitted</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f0f4f8;
        }

        .container {
            text-align: center;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
        
        @media (max-width: 600px) {
    .btn-group {
        flex-direction: column;
        gap: 10px;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Request Has Been Submitted</h1>
        <div class="btn-group">
    <a href="index.php" class="btn">Back to Home Page</a>
    <a href="loginn.php" class="btn">Login</a>
</div>
    </div>
</body>
</html>
