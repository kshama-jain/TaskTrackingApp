<?php
session_start();

require 'PHPMailer-6.9.1/src/PHPMailer.php';
require 'PHPMailer-6.9.1/src/SMTP.php';
require 'PHPMailer-6.9.1/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     http_response_code(401);
//     echo "
//     <!DOCTYPE html>
//     <html lang='en'>
//     <head>
//         <meta charset='UTF-8'>
//         <meta name='viewport' content='width=device-width, initial-scale=1.0'>
//         <title>401 Unauthorized</title>
//         <style>
//             body {
//                 font-family: Arial, sans-serif;
//                 display: flex;
//                 justify-content: center;
//                 align-items: center;
//                 height: 100vh;
//                 margin: 0;
//                 background-color: #f0f0f0;
//             }
//             .error-container {
//                 text-align: center;
//                 background-color: white;
//                 padding: 40px;
//                 border-radius: 8px;
//                 box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
//             }
//             h1 {
//                 font-size: 48px;
//                 margin: 0;
//                 color: #333;
//             }
//             p {
//                 font-size: 24px;
//                 color: #666;
//             }
//             a {
//                 display: inline-block;
//                 margin-top: 20px;
//                 padding: 10px 20px;
//                 background-color: #9acbe2;
//                 color: white;
//                 text-decoration: none;
//                 border-radius: 4px;
//             }
//             a:hover {
//                 background-color: #82b5ce;
//             }
//         </style>
//     </head>
//     <body>
//         <div class='error-container'>
//             <h1>401</h1>
//             <p>Unauthorized Access</p>
//             <a href='login.php'>Go to Login</a>
//         </div>
//     </body>
//     </html>";
//     session_destroy();
//     exit();
// }

$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$new_employee_data = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = $_POST['Name'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $phone_no = $_POST['phone'];
    $email = $_POST['email'];

    $prefix = ($role === "admin") ? "admin_" : "emp_";
    $result = $conn->query("SELECT MAX(employee_id) AS max_id FROM employees WHERE employee_id LIKE '$prefix%'");
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];

    $new_id_number = 1;
    if ($max_id) {
        $max_id_number = (int)str_replace($prefix, '', $max_id);
        $new_id_number = $max_id_number + 1;
    }
    $new_employee_id = $prefix . $new_id_number;
    $dets = $new_employee_id . "_dets"; 

    $sql = "INSERT INTO employees (employee_id, Name, password, role, phone_no, email) VALUES ('$new_employee_id', '$Name', '$password', '$role', '$phone_no', '$email')";

    if ($conn->query($sql) === TRUE) {
        $new_employee_data = [
            'employee_id' => $new_employee_id,
            'Name' => $Name,
            'role' => $role
        ];

        $create_table_sql = "CREATE TABLE $new_employee_id (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            task_description TEXT,
            task_id VARCHAR(100),
            start_date DATE,
            end_date DATE,
            status VARCHAR(50),
            comments TEXT
        )";

        if ($conn->query($create_table_sql) === TRUE) {
           

                    $mail = new PHPMailer(true);
                    try {
                       $mail->isSMTP();
                        $mail->Host       = 'mail.velveteksystems.com'; 
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'tasktracking@velveteksystems.com'; 
                        $mail->Password   = 'tasktracking'; 
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = 465;
                                

                        // Recipients
                        $mail->setFrom('tasktracking@velveteksystems.com', 'Mailer');
                         $mail->addAddress($email);

                        // Content
                        $mail->isHTML(true);                                  
                        $mail->Subject = 'New Employee Registration';
                        $mail->Body    = "Hello $Name,<br><br>Welcome to the Track Tracking App Family!
                        We're excited to have you join our community, where productivity meets efficiency. A world of organized tasks, streamlined workflows, and goal achievement awaits you.
                        <br>Employee ID: $new_employee_id<br> Password:$password<br>Role: $role";

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                } else {
                    echo "Error inserting into $dets table: " . $conn->error;
                }
            } else {
                echo "Error creating details table: " . $conn->error;
            }
        }  else {
        echo "Error: " . "<br>" . $conn->error;
    }

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #9acbe2;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
            margin-top: 20px;
        }
        button:hover {
            background-color: white;
            color: #9acbe2;
        }
        header {
            background-color: #9acbe2;
            padding: 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        nav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #9acbe2;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }
        nav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 22px;
            color: rgb(255, 255, 255);
            display: block;
            transition: 0.3s;
        }
        nav a:hover {
            background-color: #f3f6f8;
            text-decoration: none;
            font-size: 22px;
            color: rgb(105, 216, 216);
        }
        nav .closebtn {
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
        .menu-btn {
            font-size: 30px;
            cursor: pointer;
            position: absolute;
            left: 20px;
            top: 20px;
        }
    </style>
</head>
<body>
<div>
    <header>
        <span class="menu-btn" onclick="openNav()">&#9776;</span>
        <h1>EMPLOYEE TASK TRACKER</h1>
        <a href="login.php" class="signout-btn">Sign Out</a>
    </header>
    <nav id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="home.php">Home</a>
        <a href="employee_list.php">Employee List</a>
        <a href="register_team.php">Register new team</a>
        <a href="register_emp.php">Register new employee</a>
        <a href="Calendar.php">calendar</a>
        <a href="notice.php">All notices</a>
        <a href="#badges">Remove team</a>
        <a href="#badges">Remove employee</a>
    </nav>
</div>
<div class="container">
    <h1>User Registration</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="Name">Name</label>
            <input type="text" id="Name" name="Name" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
        </div>
        <div class="form-group">
            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
            </select>
        </div>
        <button type="submit">Register</button>
    </form>
</div>

<?php if ($new_employee_data): ?>
<script>
    alert("New employee registered:\n\nID: <?php echo $new_employee_data['employee_id']; ?>\nName: <?php echo $new_employee_data['Name']; ?>\nRole: <?php echo $new_employee_data['role']; ?>");
</script>
<?php endif; ?>

<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
</body>
</html>

