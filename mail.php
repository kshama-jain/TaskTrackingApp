<?php
require 'PHPMailer-6.9.1/src/PHPMailer.php';
require 'PHPMailer-6.9.1/src/SMTP.php';
require 'PHPMailer-6.9.1/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];

    $mail = new PHPMailer(true);

    try {
        
          $mail->isSMTP();
        $mail->Host       = 'mail.velveteksystems.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tasktracking@velveteksystems.com'; 
        $mail->Password   = 'tasktracking'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465;

        $mail->setFrom('tasktracking@velveteksystems.com', 'Admin');
        $mail->addAddress('tasktracking@velveteksystems.com', 'Admin');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Get in touch request';
        $mail->Body    = "Name: $name<br>Email: $email<br>Number: $number";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit;
    }

    // Send thank you email to user
    $mail = new PHPMailer(true);

    try {
      
          $mail->isSMTP();
        $mail->Host       = 'mail.velveteksystems.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tasktracking@velveteksystems.com'; 
        $mail->Password   = 'tasktracking'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465; 

        $mail->setFrom('tasktracking@velveteksystems.com', 'admin');
        $mail->addAddress($email, $name);

     

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Thank you for getting in touch!';
        $mail->Body    = "Dear $name,<br><br>Thank you for getting in touch with us. We will revert back as soon as possible.<br><br>Best regards,<br>Task Tracking App";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit;
    }

    echo "success";
}
?>

