<?php
$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT employee_id FROM employees";
$result = $conn->query($sql);

$employee_ids = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employee_ids[] = $row['employee_id'];
    }
}

$conn->close();
?>
<script>
    var employeeIds = <?php echo json_encode($employee_ids); ?>;
</script>
