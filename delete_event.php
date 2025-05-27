<?php
$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$type = $_POST['type'];

if ($type === 'event') {
    // Delete from events table
    $sql = "DELETE FROM events WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Event deleted successfully";
    } else {
        echo "Error deleting event: " . $conn->error;
    }
} else if ($type === 'task') {
    // Fetch task_id and employee_id from the events table
    $task_sql = "SELECT task_id, employee_id FROM events WHERE id = $id";
    $result = $conn->query($task_sql);

    if ($result && $row = $result->fetch_assoc()) {
        $task_id = $row['task_id'];
        $employee_id = $row['employee_id'];

        // Delete from events table
        $sql = "DELETE FROM events WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Delete from employee table where task_id matches
            $sql_employee = "DELETE FROM $employee_id WHERE task_id = $task_id";
            if ($conn->query($sql_employee) === TRUE) {
                echo "Task and associated records deleted successfully";
            } else {
                echo "Error deleting task from employee_tasks table: " . $conn->error;
            }
        } else {
            echo "Error deleting event/task: " . $conn->error;
        }
    } else {
        echo "No task found for the given event/task ID.";
    }
}

$conn->close();
