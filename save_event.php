<?php
session_start();
$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_POST['id']) ? $_POST['id'] : null;
$title = isset($_POST['title']) ? $_POST['title'] : '';
$start = isset($_POST['start']) ? $_POST['start'] : '';
$type = isset($_POST['taskEventType']) ? $_POST['taskEventType'] : '';
$employee_id = isset($_POST['employee']) ? $_POST['employee'] : '';
$end = isset($_POST['end']) && !empty($_POST['end']) ? $_POST['end'] : $start;

if ($id) {
    
    $stmt = $conn->prepare("SELECT employee_id, task_id FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_employee_id = $row['employee_id'];
    $task_id = $row['task_id'];
    $stmt->close();

    if ($current_employee_id !== $employee_id) {
        $stmt = $conn->prepare("DELETE FROM `$current_employee_id` WHERE task_id = ?");
        $stmt->bind_param("s", $task_id);
        $stmt->execute();
        $stmt->close();

        $sql = "INSERT INTO `$employee_id` (task_description, task_id, start_date, end_date, status) VALUES ('$title', '$task_id', '$start', '$end', 'assigned')";
        if ($conn->query($sql) === TRUE) {
            echo "Task moved and new record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $sql = "UPDATE events SET title = '$title', start = '$start', end = '$end', employee_id = '$employee_id' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Event updated successfully";
    } else {
        echo "Error updating event: " . $conn->error;
    }
    $fetch_phone_sql = "SELECT phone_no FROM employee WHERE employee_id = '$employee_id'";
$phone_result = $conn->query($fetch_phone_sql);

if ($phone_result->num_rows > 0) {
    $phone_row = $phone_result->fetch_assoc();
    $phone_no = $phone_row['phone_no'];
    include("notifications\update_notification.php");
}
    $sql = "UPDATE `$employee_id` SET task_description = '$title', start_date = '$start', end_date = '$end' WHERE task_id = '$task_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Employee event updated successfully";
    } else {
        echo "Error updating employee event: " . $conn->error;
    }
    $id = null; 
    $conn->close();
} else {
    $task_id = '';
    $id = null; 
    if ($type == 'task') {
        $result = $conn->query("SELECT MAX(task_id) AS max_task_id FROM events WHERE type = 'task'");
        $row = $result->fetch_assoc();
        $task_id = $row['max_task_id'] ? $row['max_task_id'] + 1 : 1;
        $status='assigned';
    }

    $sql = "INSERT INTO events (title, start, end, type, employee_id, task_id, status) VALUES ('$title', '$start', '$end', '$type', '$employee_id', '$task_id', '$status')";
    if ($conn->query($sql) === TRUE) {
        echo "New event created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql = "INSERT INTO `$employee_id` (task_description, task_id, start_date, end_date, status) VALUES ('$title', '$task_id', '$start', '$end', 'assigned')";
    if ($conn->query($sql) === TRUE) {
        echo "New task created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $fetch_phone_sql = "SELECT phone_no FROM employee WHERE employee_id = '$employee_id'";
    $phone_result = $conn->query($fetch_phone_sql);
    
    if ($phone_result->num_rows > 0) {
        $phone_row = $phone_result->fetch_assoc();
        $phone_no = $phone_row['phone_no'];
        include("notifications\add_notification.php");
    }
    
    $id = null; 
    $conn->close();
        
}

