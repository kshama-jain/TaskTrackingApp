<?php
// update_team.php

$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_id = $_POST['team_id'];
    $team_name = $_POST['team_name'];
    
    $employee_ids = isset($_POST['employee_ids']) ? $_POST['employee_ids'] : [];


    if (!empty($employee_ids)) {
        $stmt = $conn->prepare("UPDATE employees SET team_id = ? WHERE employee_id = ?");
        foreach ($employee_ids as $employee_id) {
            $stmt->bind_param("ss", $team_id, $employee_id);
            $stmt->execute();
        }
        $stmt = $conn->prepare("UPDATE employees SET team = ? WHERE employee_id = ?");
        foreach ($employee_ids as $employee_id) {
            $stmt->bind_param("ss", $team_name, $employee_id);
            $stmt->execute();
        }
        $stmt->close();
    }

    $conn->close();
    echo "Team members updated successfully.";
}
