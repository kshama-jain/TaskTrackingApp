<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}
$employee_id = $_SESSION['employee_id'];

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    $taskId =  ($_POST['title']);
   $newStatus = ($_POST['status']);
    $newComment = ($_POST['comment']);
    $employee_id = $_SESSION['employee_id'];
    $id=$_POST['task_id'];
   
   

    // Prepare the SQL statement to update the status
    $sql = "UPDATE events SET status = ?, comments = ? WHERE task_id = ?";

    // Initialize a statement and prepare the SQL query
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters to the SQL query
    $stmt->bind_param("ssi", $newStatus, $newComment,$id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Good Job!!";
    } else {
        echo "Error updating task status: " . $stmt->error;
    }
    $sql = "UPDATE $employee_id SET status = ?, comments = ? WHERE task_id = ?";

// Initialize a statement and prepare the SQL query
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Bind parameters to the SQL query
$stmt->bind_param("ssi", $newStatus, $newComment, $id);

// Execute the statement
if ($stmt->execute()) {
    echo "Task status and comment updated successfully.";
} else {
    echo "Error updating task status and comment: " . $stmt->error;
}

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>
