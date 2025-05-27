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

function getTeamDetails($conn, $id) {
    $stmt = $conn->prepare("SELECT team_id, team_name FROM teams WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $team = $result->fetch_assoc();
    $stmt->close();
    return $team;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $team = getTeamDetails($conn, $id);
    if ($team) {
        $team_name = $team['team_name'];
        $team_id = $team['team_id'];
    } else {
        $team_name = "Team not found";
        $team_id = null;
    }
}

$stmt_employees = $conn->prepare("SELECT employee_id, Name FROM employees WHERE team_id IS NULL OR team_id = ''");
$stmt_employees->execute();
$result_employees = $stmt_employees->get_result();
$available_employees = [];
while ($row = $result_employees->fetch_assoc()) {
    $available_employees[] = $row;
}
$stmt_employees->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Team</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h4 {
            text-align: center;
            color: #333;
        }
        header {
            background-color: #9acbe2;
            padding: 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        nav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #9acbe2;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }
        nav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 22px;
            color: rgb(255, 255, 255);
            display: block;
            transition: 0.3s;
        }
        nav a:hover {
            background-color: #f3f6f8;
            text-decoration: none;
            font-size: 22px;
            color: rgb(105, 216, 216);
        }
        nav .closebtn {
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
        .menu-btn {
            font-size: 30px;
            cursor: pointer;
            position: absolute;
            left: 20px;
            top: 20px;
        }
        .add-task-btn {
            text-align: center;
        }
        .modal-body {
            max-height: 400px;
            overflow-y: scroll;
        }
    </style>
</head>
<body>
    <header>
        <span class="menu-btn" onclick="openNav()">&#9776;</span>
        <h1>EMPLOYEE TASK TRACKER</h1>
        <a href="login.php" class="signout-btn">Sign Out</a>
    </header>

    <nav id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="home.php">Home</a>
        <a href="employee_list.php">Employee List</a>
        <a href="register_team.php">Register new team</a>
        <a href="register_emp.php">Register new employee</a>
        <a href="Calendar.php">Calendar</a>
        <a href="notice.php">All notices</a>
        <a href="#badges">Remove employee</a>
    </nav>

    <div class="container">
        <h4>Edit Team: <?php echo htmlspecialchars($team_name); ?></h4>
        <h4>(<?php echo htmlspecialchars($team_id); ?>)</h4>
        <button class="add-task-btn btn btn-primary" onclick="showAddTaskModal()">+ ADD EMPLOYEES</button>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Employee ID</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($team_id) {
                    $stmt_team_employees = $conn->prepare("SELECT employee_id, Name, role FROM employees WHERE team_id = ?");
                    $stmt_team_employees->bind_param("s", $team_id); 
                    $stmt_team_employees->execute();
                    $result_team_employees = $stmt_team_employees->get_result();
                    if ($result_team_employees->num_rows > 0) {
                        while ($row = $result_team_employees->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["Name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["employee_id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                            echo "<td>
                            <button class='btn btn-danger btn-sm' onclick='deleteEmployee(" . htmlspecialchars($row["employee_id"]) . ")'>Delete</button>
                        </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No employees available</td></tr>";
                    }
                    $stmt_team_employees->close();
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
<!-- Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add Employees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="addEmployeesForm">
    <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($team_id); ?>">
    <input type="hidden" name="team_name" value="<?php echo htmlspecialchars($team_name); ?>">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Select</th>
                <th scope="col">Name</th>
                <th scope="col">Employee ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($available_employees as $employee): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="employee_ids[]" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">
                    </td>
                    <td><?php echo htmlspecialchars($employee['Name']); ?></td>
                    <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveChanges()">Save Changes</button>
            </div>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        function showAddTaskModal() {
            $('#addEmployeeModal').modal('show');
        }

     function saveChanges() {
    var form = document.getElementById('addEmployeesForm');
    var formData = new FormData(form);

    // Create a new FormData object for the selected employees
    var newFormData = new FormData();
    newFormData.append('team_id', formData.get('team_id'));
    newFormData.append('team_name', formData.get('team_name'));

    // Get selected employee IDs
    const selectedEmployees = Array.from(document.querySelectorAll('input[name="employee_ids[]"]:checked'))
        .map(checkbox => checkbox.value);

    // Append only the selected employee IDs to the new FormData object
    selectedEmployees.forEach(id => newFormData.append('employee_ids[]', id));

    fetch('update_team.php', {
        method: 'POST',
        body: newFormData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Response data:", data); 
        alert(data); // Show the response message
        $('#addEmployeeModal').modal('hide'); // Hide the modal
        location.reload(); // Reload the page
    })
    .catch(error => console.error('Error:', error));
}

</script>

</body>
</html>
