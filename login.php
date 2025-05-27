<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $password = $_POST['password'];

    $sql = "SELECT employee_id, role FROM employees WHERE employee_id = '$employee_id' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['employee_id'] = $row['employee_id'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {
            header("Location: home.php");
        } else if ($row['role'] == 'employee') {
            header("Location: home_.php");
        } else {
            echo "<script>alert('Unknown role. Please contact administrator.');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid employee ID or password. Please try again.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-right: 50px;
            overflow: hidden;
        }

        header {
            background-color: #011425eb;
            padding: 30px;
            text-align: center;
            color: white;
            width: 100%;
            height: 100px;
            box-sizing: border-box;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            flex-grow: 1;
        }

        .back-btn {
            background-color: #6c757d;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            margin-right: auto;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .login-box {
            width: 350px;
            background-color: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
            z-index: 10;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-group button {
            background-color: #011425eb;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
        }

        .form-group button:hover {
            background-color: #2B7A78;
        }

        .form-group .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .form-group .forgot-password a {
            color: #2B7A78;
            text-decoration: none;
        }

        .form-group .forgot-password a:hover {
            text-decoration: underline;
        }

        .arr {
            position: relative;
            background: white;
            border-radius: 50%;
            margin: 15px;
            height: 40px;
            width: 40px;
            transition: 0.4s ease;
        }

        .arr:hover {
            box-shadow: 0px 0px 5px 5px #6b6666;
        }

        .arr div {
            position: absolute;
            height: 15px;
            width: 15px;
            border-top: 4px solid #202020;
            border-left: 4px solid #202020;
            transform: rotate(45deg);
            left: 12px;
            top: 16px;
        }

        .left {
            transform: rotate(-90deg);
        }

        .btn onClick {
            color: black;
        }

        .bubble {
            position: absolute;
            width: 50px;
            height: 50px;
            background-color: rgba(43, 122, 120, 0.7);
            border-radius: 50%;
            animation: bubble 10s infinite;
        }

        @keyframes bubble {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh);
                opacity: 0;
            }
        }

        @keyframes bubble-reverse {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        .bubble:nth-child(2) {
            width: 70px;
            height: 70px;
            left: 20%;
            animation-duration: 12s;
            animation: bubble-reverse 12s infinite;
        }

        .bubble:nth-child(3) {
            width: 40px;
            height: 40px;
            left: 40%;
            animation-duration: 8s;
        }

        .bubble:nth-child(4) {
            width: 60px;
            height: 60px;
            left: 60%;
            animation-duration: 11s;
        }

        .bubble:nth-child(5) {
            width: 45px;
            height: 45px;
            left: 80%;
            animation-duration: 13s;
            animation: bubble-reverse 13s infinite;
        }

        .bubble:nth-child(6) {
            width: 55px;
            height: 55px;
            left: 30%;
            animation-duration: 9s;
        }

        .bubble:nth-child(7) {
            width: 65px;
            height: 65px;
            left: 70%;
            animation-duration: 10s;
            animation: bubble-reverse 10s infinite;
        }

        .bubble:nth-child(8) {
            width: 50px;
            height: 50px;
            left: 10%;
            animation-duration: 14s;
        }

        .bubble:nth-child(9) {
            width: 50px;
            height: 50px;
            left: 50%;
            animation-duration: 15s;
            animation: bubble-reverse 15s infinite;
        }

        .bubble:nth-child(10) {
            width: 50px;
            height: 50px;
            left: 90%;
            animation-duration: 16s;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <header>
        <div class="arr left" onclick="window.location.href='main.php';"><div> </div></div>
        <h1>EMPLOYEE TASK TRACKER</h1>
    </header>

    <div class="login-box shadow p-3 bg-white mb-7 rounded">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee ID</label>
                <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Please Enter Your Employee id" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Please Enter Your Password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn" id="myButton">Login</button>
            </div>
            <div class="form-group forgot-password">
                <a style="color:#2B7A78;" href="forgot_password.php">Forgot Password?</a>
            </div>
        </form>
    </div>

    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    
    

    <script>
        var myButton = document.getElementById('myButton');
        myButton.addEventListener('click', function() {
            myButton.style.backgroundColor = '#2B747A';
        });
    </script>
</body>
</html>
