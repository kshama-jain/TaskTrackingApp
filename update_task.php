<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$employee_id = $_SESSION['employee_id'];

$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$response = ['success' => true, 'message' => 'Tasks updated successfully'];

$task_ids = $_POST['task_id'];
$comments = $_POST['comment'];
$status = $_POST['status'];

foreach ($task_ids as $index => $task_id) {
    $comment = isset($comments[$index]) ? $mysqli->real_escape_string($comments[$index]) : '';
    $status_key = 'status' . $task_id;
    $task_status = isset($_POST[$status_key]) ? $mysqli->real_escape_string($_POST[$status_key]) : '';

    if ($task_status && $task_id) {
        // Update employee-specific table
        $update_employee_table = "UPDATE $employee_id SET status = ?, comment = ? WHERE id = ?";
        $stmt = $mysqli->prepare($update_employee_table);
        $stmt->bind_param('ssi', $task_status, $comment, $task_id);

        if (!$stmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to update employee table';
            break;
        }

        // Update events table
        $update_events_table = "UPDATE events SET status = ?, comment = ? WHERE task_id = ?";
        $stmt = $mysqli->prepare($update_events_table);
        $stmt->bind_param('ssi', $task_status, $comment, $task_id);

        if (!$stmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to update events table';
            break;
        }
    }
}

$mysqli->close();

echo json_encode($response);
?>
 