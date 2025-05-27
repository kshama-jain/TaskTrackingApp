<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$team = isset($_GET['team']) ? $_GET['team'] : '';

$sql = "SELECT employee_id FROM employees WHERE team = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $team);
$stmt->execute();
$result = $stmt->get_result();

$employees = array();
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($employees);
?>
