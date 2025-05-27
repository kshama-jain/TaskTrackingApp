<?php
// Database connection
$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total projects
$projectResult = $conn->query("SELECT COUNT(*) AS total_projects FROM projects");
$totalProjects = $projectResult->fetch_assoc()['total_projects'];

// Fetch total tasks and active tasks
$taskResult = $conn->query("SELECT COUNT(*) AS total_tasks, SUM(status = 'Active') AS active_tasks FROM tasks");
$taskData = $taskResult->fetch_assoc();
$totalTasks = $taskData['total_tasks'];
$activeTasks = $taskData['active_tasks'];

// Fetch task performance
$performanceResult = $conn->query("SELECT 
    SUM(status = 'Completed') AS completed_tasks,
    SUM(status = 'Active') AS active_tasks,
    SUM(status = 'Assigned') AS assigned_tasks 
FROM tasks");
$performanceData = $performanceResult->fetch_assoc();

// Fetch tasks distribution over months
$distributionResult = $conn->query("SELECT 
    DATE_FORMAT(created_at, '%b') AS month, 
    COUNT(*) AS task_count 
FROM tasks 
GROUP BY month 
ORDER BY MONTH(created_at)");

$taskDistribution = [];
while ($row = $distributionResult->fetch_assoc()) {
    $taskDistribution[] = $row;
}

// Fetch meetings
$meetingsResult = $conn->query("SELECT * FROM tasks WHERE status = 'Meeting'");
$projectsResult = $conn->query("SELECT * FROM projects");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.5rem;
        }
        .task-performance-container {
            position: relative;
        }
        .task-performance-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.2rem;
            text-align: center;
        }
        .issue-card {
            border-left: 5px solid;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .meeting { border-color: #28a745; }
        .project { border-color: #007bff; }
    </style>
</head>
<body>
<header class="d-flex align-items-center p-3 bg-light border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <button class="btn btn-light" id="menu-toggle"><i class="bi bi-list"></i></button>
                    <span class="ms-2">Task</span>
                </div>
                <div class="col text-end">
                    <div class="d-inline-flex align-items-center">
                       
                        <i class="bi bi-bell me-3"></i>
                        <i class="bi bi-gear me-3"></i>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="profile.jpg" alt="Profile" class="rounded-circle" width="30">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container mt-5">
        <h2>WELCOME!!</h2>
        <h3>Dashboard</h3>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Task Performance</h5>
                        <div class="task-performance-container">
                            <canvas id="taskPerformanceChart"></canvas>
                            <div class="task-performance-text">
                                <p>Completed: <?php echo $performanceData['completed_tasks']; ?></p>
                                <p>Active: <?php echo $performanceData['active_tasks']; ?></p>
                                <p>Assigned: <?php echo $performanceData['assigned_tasks']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Task Distribution Over Months</h5>
                        <canvas id="taskDistributionChart"></canvas>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Meetings</h5>
                        <?php while ($meeting = $meetingsResult->fetch_assoc()): ?>
                            <div class="issue-card meeting">
                                <h6><?php echo $meeting['task_name']; ?></h6>
                                <p>Date: <?php echo $meeting['date']; ?></p>
                                <p>Time: <?php echo $meeting['time']; ?></p>
                                <p>Status: <?php echo $meeting['status']; ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <h5>
                    <a data-toggle="collapse" href="#collapseProjects" role="button" aria-expanded="false" aria-controls="collapseProjects">
                        Projects
                    </a>
                </h5>
                <div class="collapse show" id="collapseProjects">
                    <?php while ($project = $projectsResult->fetch_assoc()): ?>
                    <div class="issue-card project">
                        <h6><?php echo $project['project_name']; ?></h6>
                        <p>Start Date: <?php echo $project['start_date']; ?></p>
                        <p>Due Date: <?php echo $project['due_date']; ?></p>
                        <p>Status: <?php echo $project['status']; ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="col-md-6">
                <h5>
                    <a data-toggle="collapse" href="#collapseChat" role="button" aria-expanded="false" aria-controls="collapseChat">
                        Chat
                    </a>
                </h5>
                <div class="collapse show" id="collapseChat">
                    <div class="card">
                        <div class="card-body">
                            <div class="chat-container">
                                <!-- Chat interface would go here -->
                                <p>Chat functionality to be implemented</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Task Performance Chart
        var ctx = document.getElementById('taskPerformanceChart').getContext('2d');
        var taskPerformanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Active', 'Assigned'],
                datasets: [{
                    label: '# of Tasks',
                    data: [<?php echo $performanceData['completed_tasks']; ?>, <?php echo $performanceData['active_tasks']; ?>, <?php echo $performanceData['assigned_tasks']; ?>],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Task Distribution Chart
        var ctx2 = document.getElementById('taskDistributionChart').getContext('2d');
        var taskDistributionChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($taskDistribution as $data) { echo "'" . $data['month'] . "',"; } ?>],
                datasets: [{
                    label: '# of Tasks',
                    data: [<?php foreach ($taskDistribution as $data) { echo $data['task_count'] . ","; } ?>],
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
