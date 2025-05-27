<?php
// Include database connection
$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require 'PHPMailer-6.9.1/src/PHPMailer.php';
require 'PHPMailer-6.9.1/src/SMTP.php';
require 'PHPMailer-6.9.1/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $email = $_POST['email'];

    // Debugging output
    echo 'Employee ID: ' . htmlspecialchars($employee_id) . '<br>';
    echo 'Email: ' . htmlspecialchars($email) . '<br>';

    // Check if employee_id and email match
    $stmt = $conn->prepare("SELECT email FROM employees WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($row['email'] == $email) {
            // Generate reset code
            $reset_code = bin2hex(random_bytes(16)); // Generate a secure random token

            // Send reset code via email
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host       = 'mail.velveteksystems.com'; // SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tasktracking@velveteksystems.com'; // SMTP username
            $mail->Password   = 'tasktracking'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL/TLS
            $mail->Port       = 465; // SMTP port

            $mail->setFrom('tasktracking@velveteksystems.com', 'Admin');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset Code';
            $mail->Body    = 'Your password reset code is: ' . $reset_code;

            if ($mail->send()) {
                // Save reset code in session
                session_start();
                $_SESSION['reset_code'] = $reset_code;
                $_SESSION['reset_email'] = $email;
                
                echo 'Reset code sent to your email. <a href="reset_password.php">Click here to reset your password</a>';
            } else {
                echo 'Failed to send reset code. Please try again.';
            }
        } else {
            echo 'Email does not match our records.';
        }
    } else {
        echo 'Employee ID not found.';
    }
}
?>

<form method="POST" action="">
    Employee ID: <input type="text" name="employee_id" required><br>
    Email: <input type="email" name="email" required><br>
    <input type="submit" value="Send Reset Code">
</form>
