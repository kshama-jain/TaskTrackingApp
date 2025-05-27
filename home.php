<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(401);
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>401 Unauthorized</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f0f0f0;
            }
            .error-container {
                text-align: center;
                background-color: white;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                font-size: 48px;
                margin: 0;
                color: #333;
            }
            p {
                font-size: 24px;
                color: #666;
            }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #9acbe2;
                color: white;
                text-decoration: none;
                border-radius: 4px;
            }
            a:hover {
                background-color: #82b5ce;
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <h1>401</h1>
            <p>Unauthorized Access</p>
            <a href='login.php'>Go to Login</a>
        </div>
    </body>
    </html>";
    session_destroy();
    exit();
}

$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";


$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$queryAssignedTasks = "SELECT COUNT(*) AS count FROM events WHERE status = 'assigned' AND (start = CURDATE() OR end = CURDATE() OR CURDATE() BETWEEN start AND end  OR start>CURDATE() OR end> CURDATE())";
$queryCompletedTasks = "SELECT COUNT(*) AS count FROM events WHERE status = 'completed'";
$querySkippedTasks = "SELECT COUNT(*) AS count FROM events WHERE status = 'skipped'";
$queryPendingTasks = "SELECT COUNT(*) AS count FROM events WHERE status = 'assigned' AND (start < CURDATE() OR end < CURDATE())";
$queryInProgressTasks = "SELECT COUNT(*) AS count FROM events WHERE status = 'inprogress'";

$assignedTasksCount = $completedTasksCount = $skippedTasksCount = $pendingTasksCount = $inProgressTasksCount = 0;

if ($result = $mysqli->query($queryAssignedTasks)) {
    $assignedTasksCount = $result->fetch_assoc()['count'];
    $result->free();
}

if ($result = $mysqli->query($queryCompletedTasks)) {
    $completedTasksCount = $result->fetch_assoc()['count'];
    $result->free();
}

if ($result = $mysqli->query($querySkippedTasks)) {
    $skippedTasksCount = $result->fetch_assoc()['count'];
    $result->free();
}

if ($result = $mysqli->query($queryPendingTasks)) {
    $pendingTasksCount = $result->fetch_assoc()['count'];
    $result->free();
}

if ($result = $mysqli->query($queryInProgressTasks)) {
    $inProgressTasksCount = $result->fetch_assoc()['count'];
    $result->free();
}
$TotalCount = $assignedTasksCount + $completedTasksCount + $skippedTasksCount + $pendingTasksCount + $inProgressTasksCount;

$mysqli->close();
?>
<?php
$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT task_id, employee_id, title, comments FROM events WHERE comments IS NOT NULL AND comments != ''";
$result = $conn->query($sql);

$tasks_with_comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks_with_comments[] = $row;
    }
}

$conn->close();
?>

        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Task Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        header {
    background-color: #0F054C;
    padding: 10px;
    text-align: center;
    color: white;
    position: relative;
}

.profile-container {
    display: flex;
    align-items: center;
    cursor: pointer;
    position: absolute; 
    right: 20px; 
    top: 50%; 
    transform: translateY(-50%); 
    z-index: 1000; 
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
            top: 10px;
        }
.profile-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.username {
    color: white;
    margin-right: 10px;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 50px;
    right: 0;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.dropdown-menu a {
    display: block;
    padding: 10px;
    color: #333;
    text-decoration: none;
    font-size: 16px;
}

.dropdown-menu a:hover {
    background-color: #f0f0f0;
}

        .main-content {
            padding: 20px;
            transition: margin-left .5s;
        }
        .h-section {
            background: linear-gradient(135deg, #f9f9f9 0%, #fefefe 100%);
            text-align: center;
            padding: 20px 10px;
        }
        .h-section h1 {
            font-size: 48px;
            font-weight: 700;
            margin: 0;
            color: #9acbe2;
        }
        .signout-btn {
            font-size: 18px;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 10px;
            color: white; 
            background-color: transparent; 
            border: none;
            transition: background-color 0.3s;
            padding: 8px 12px; 
        }
  
        .signout-btn:hover {
            background-color: #575757; 
        }

        footer {
            background-color: #9acbe2;
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        footer .social-icons img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }
        footer .social-icons img:hover {
            transform: scale(1.2);
        }
        .footer-left, .footer-right {
            flex: 1;
        }

        .footer-left h3, .footer-right h3 {
            font-size: 18px;
            margin-bottom: 0px;
        }

        .footer-left p {
            font-size: 14px;
            margin: 5px 0;
        }

        .social-icons {
            margin-top: 10px;
        }

        .social-icons a {
            display: inline-block;
            margin-right: 10px;
        }

        .social-icons img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .footer-bottom {
            background-color: #9acbe2;
            padding: 10px 0;
            font-size: 12px;
        }

        .dashboard-box {
            color: white;
            text-align: center;
            padding: 10px;
            margin: 5px;
            border-radius: 2px;
            transition: transform 0.3s;
        }

        .dashboard-box:hover {
            transform: scale(1.05);
        }

        .assigned-tasks { background-color:#9acbe2; }
        .completed-tasks { background-color: #9acbe2; }
        .skipped-tasks { background-color: #9acbe2; }
        .pending-tasks { background-color:#9acbe2; }
        .in-progress-tasks { background-color:#9acbe2; }

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: stretch;
            margin:10px 0;
        }
        .dashboard-box button {
            width: 100%;
            padding: 5px;
            background-color: #82b5ce;
            color: white;
            border: none;
            border-radius: 1px;
            font-size: 10px;
            margin-top: -10px;
            transition: background-color 0.3s;
        }
        .dashboard-container .dashboard-box {
    flex: 1;
    min-width: 50px;
    max-height: 110px;
}


.chart-container {
            width: 60%;
            margin-top: 30px; 
            text-align: right;
            float: left; 
        }

        .meetings-container {
            width: 35%;
            margin-top: 30px; 
            text-align: left;
            float: right;
            height: 300px; 
            border: #575757;
            overflow-y:scroll; 
        }

        .meetings-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            border: #575757;
            color: #333;
        }

        .meetings-list {
            list-style: none;
            padding: 0;
        }

        .meetings-list li {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .meetings-list li:hover {
            background-color: #eaeaea;
        }
        canvas {
            width: 100% !important;
            height:350px !important
        }
        html, body {
    height: 100%;
    margin: 0;
}

.main-content {
    flex: 1;
}

.dashboard-container {
    margin: 10px 0;
}

        
    </style>
</head>
<body class="menu-open">
    <div>
    <header>
    <span class="menu-btn" onclick="openNav()">&#9776;</span>
    <h1>EMPLOYEE TASK TRACKER</h1>
    <div class="profile-container">
        <img src="main.jpg" alt="Profile Photo" class="profile-photo" onclick="toggleDropdown()">
        <span class="username"><?php echo $_SESSION['employee_id']; ?></span>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="#">Profile</a>
            <a href="#">Settings</a>
            <a href="login.php">Sign Out</a>
        </div>
    </div>
</header>


        <nav id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="home.php">Home</a>
            <a href="employee_list.php">Employee List</a>
            <a href="register_team.php">Register new team</a>
            <a href="register_emp.php">Register new employee</a>
            <a href="Calendar.php">Calendar</a>
            <a href="notice.php">All notices</a>
            <a href="remove_team.php">Remove team</a>
            <a href="#badges">Remove employee</a>
        </nav>

        <div class="main-content" id="main">
                <div class="dashboard-container">
                <div class="dashboard-box assigned-tasks">
                        <h5>All Tasks         </h5>
                        <p><?php echo $TotalCount; ?></p>
                        <button onclick="window.location.href='all_task.php'">Click Here</button>
                    </div>
                    <div class="dashboard-box assigned-tasks">
                        <h5>Assigned Tasks</h5>
                        <p><?php echo $assignedTasksCount; ?></p>
                        <button onclick="window.location.href='assigned_task.php'">Click Here</button>
                    </div>
                    <div class="dashboard-box completed-tasks">
                        <h5>Completed Tasks</h5>
                        <p><?php echo $completedTasksCount; ?></p>
                        <button onclick="window.location.href='completed_task.php'">Click Here</button>
                    </div>
                    <div class="dashboard-box skipped-tasks">
                        <h5>Skipped Tasks</h5>
                        <p><?php echo $skippedTasksCount; ?></p>
                        <button onclick="window.location.href='skipped_task.php'">Click Here</button>
                    </div>
                    <div class="dashboard-box pending-tasks">
                        <h5>Pending Tasks</h5>
                        <p><?php echo $pendingTasksCount; ?></p>
                        <button onclick="window.location.href='pending_task.php'">Click Here</button>
                    </div>
                    <div class="dashboard-box in-progress-tasks">
                        <h5>In-Progress Tasks</h5>
                        <p><?php echo $inProgressTasksCount; ?></p>
                        <button onclick="window.location.href='inprogress_task.php'">Click Here</button>
                    </div>
                </div>
                <div class="chart-container">
        <canvas id="taskChart"></canvas>
    </div>
    <div class="meetings-container">
            <h2>Comments to respond to:</h2>
            <?php foreach ($tasks_with_comments as $task): ?>
                <div class="task">
                    <h5>Task: <?php echo htmlspecialchars($task['task_description']); ?> (Employee ID: <?php echo htmlspecialchars($task['employee_id']); ?>)</h5>
                    <p>Comment: <?php echo htmlspecialchars($task['comments']); ?></p>
                </div>
            <?php endforeach; ?>
            <?php if (empty($tasks_with_comments)): ?>
                <p>No tasks with comments to display.</p>
            <?php endif; ?>
</div>   
    </div>
    <div>

    </div>
    <script>
        function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

window.onclick = function(event) {
    if (!event.target.matches('.profile-photo')) {
        const dropdown = document.getElementById('dropdownMenu');
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        }
    }
}

        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
        const labels = ["All","Assigned","Completed", "Skipped", "In-Progress","Pending"];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Tasks Overview',
                data: [<?php echo $TotalCount; ?>,<?php echo $assignedTasksCount; ?>,<?php echo$completedTasksCount; ?>, <?php echo $skippedTasksCount; ?>, <?php echo $inProgressTasksCount; ?>,<?php echo $pendingTasksCount; ?>],
                borderColor: 'white',
                backgroundColor: 'blue',
                fill: true,
            }]
        };
        const ctx = document.getElementById('taskChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
