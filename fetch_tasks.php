<?php
session_start();

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php"); 
    exit();
}


$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);


$employee_id = $_SESSION['employee_id'];
$tableName =  $employee_id;

$sql = "SELECT task_description AS title, start_date AS start, end_date AS end, status FROM $tableName";

$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['start'] = ($row['start']); 
        $row['end'] = ($row['end']); 
        $events[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($events);

$conn->close();

