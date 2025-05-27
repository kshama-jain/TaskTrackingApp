<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reset_code = $_POST['reset_code'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate reset code and passwords
    if ($reset_code === $_SESSION['reset_code'] && $new_password === $confirm_password) {
        $email = $_SESSION['reset_email'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Securely hash the new password

        // Update the password
        $stmt = $conn->prepare("UPDATE employees SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            echo 'Password updated successfully.';
        } else {
            echo 'Failed to update password. Please try again.';
        }

        // Unset session variables
        unset($_SESSION['reset_code']);
        unset($_SESSION['reset_email']);
    } else {
        echo 'Invalid reset code or passwords do not match.';
    }
}
?>

<form method="POST" action="">
    Reset Code: <input type="text" name="reset_code" required><br>
    New Password: <input type="password" name="new_password" required><br>
    Confirm Password: <input type="password" name="confirm_password" required><br>
    <input type="submit" value="Reset Password">
</form>
