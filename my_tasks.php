<?php
session_start();

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php"); 
    exit();
}

$employee_id = $_SESSION['employee_id'];

$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";


$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$table_name = $employee_id;

// Query to count the number of tasks by status
$query = "SELECT 
            COUNT(*) AS all_tasks, 
            SUM(status = 'completed') AS completed_tasks, 
            SUM(status = 'pending') AS pending_tasks, 
            SUM(status = 'skipped') AS skipped_tasks, 
            SUM(status = 'inprogress') AS incomplete_tasks 
          FROM $table_name";

$result = $mysqli->query($query);
$counts = $result->fetch_assoc();

$query = "SELECT task_description, start_date, end_date, status FROM $table_name";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($task_description, $start_date, $end_date, $status);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 0px;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #dddddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #ddd;
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
       
        .menu-open #calendar {
            margin-left: 250px; 
        }

        .container {
            width: 80%; 
            margin: auto; 
            padding-top: 10px; 
            z-index: 0; 
        }
        
        .dashboard {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .dashboard .stat {
            text-align: center;
            flex: 1;
        }
        .dashboard .stat h3 {
            margin: 0;
            font-size: 18px; 
        }
        .dashboard .stat p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .dashboard .stat canvas {
            width: 50px; 
            height: 50px; 
            margin: 0 auto; 
        }

        .alert {
            padding: 10px;
            background-color: #d9edf7;
            color: #31708f;
            border: 1px solid #bce8f1;
            border-radius: 4px;
            margin: 20px 0;
        }
        .pagination {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }
        .pagination .page-size {
            display: flex;
            align-items: center;
        }
        .pagination .page-size select {
            margin-left: 10px;
            padding: 5px;
        }
    </style>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="menu">
    <header class="header">
        <span class="menu-btn" onclick="openNav()">&#9776;</span>
        <h1>EMPLOYEE TASK TRACKER</h1>
        <a href="login.php" class="signout-btn">Sign Out</a>
    </header>
    <nav id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="home_.php">Home</a>
            <a href="#About Us">Profile</a>
            <a href="#absence">Mark absence</a>
            <a href="my_tasks.php">My tasks</a>
            <a href="task_calendar.php">calendar</a>
            <a href="notices.php">All notices</a>
            <a href="#badges">My Badges</a>
            <a href="#badges">Leaderboard</a>
        </nav>

    <div class="container">
        <div class="dashboard">
            <div class="stat">
                <canvas id="allTasksChart" width="100" height="100"></canvas>
                <p>All Tasks</p>
                <p><?php echo $counts['all_tasks']; ?></p>
            </div>
            <div class="stat">
                <canvas id="completedTasksChart" width="100" height="100"></canvas>
                <p>Completed Tasks</p>
                <p><?php echo $counts['completed_tasks']; ?></p>
            </div>
            <div class="stat">
                <canvas id="pendingTasksChart" width="100" height="100"></canvas>
                <p>Pending Tasks</p>
                <p><?php echo $counts['pending_tasks']; ?></p>
            </div>
            <div class="stat">
                <canvas id="skippedTasksChart" width="100" height="100"></canvas>
                <p>Skipped Tasks</p>
                <p><?php echo $counts['skipped_tasks']; ?></p>
            </div>
            <div class="stat">
                <canvas id="incompleteTasksChart" width="100" height="100"></canvas>
                <p>In-Progress Tasks</p>
                <p><?php echo $counts['incomplete_tasks']; ?></p>
            </div>
        </div>

        <div class="pagination">
            <div class="page-size">
                <label for="filter">Filter:</label>
                <select id="filter">
            <option value="all">All tasks</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="skipped">Skipped</option>
            <option value="incomplete">Incomplete</option>
            </select>
            </div>
            <div class="page-nav">
                <button>&laquo; Previous</button>
                <button>Next &raquo;</button>
            </div>
        </div>

        <table>
            <tr>
                <th>Task Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
            <?php
            while ($stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($task_description) . '</td>';
                echo '<td>' . htmlspecialchars($start_date) . '</td>';
                echo '<td>' . htmlspecialchars($end_date) . '</td>';
                echo '<td>' . htmlspecialchars($status) . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <script>

        const allTasks = <?php echo $counts['all_tasks']; ?>;
        const completedTasks = <?php echo $counts['completed_tasks']; ?>;
        const pendingTasks = <?php echo $counts['pending_tasks']; ?>;
        const skippedTasks = <?php echo $counts['skipped_tasks']; ?>;
        const incompleteTasks = <?php echo $counts['incomplete_tasks']; ?>;

        function renderChart(id, value, maxValue, color, totalCount) {
            const ctx = document.getElementById(id).getContext('2d');
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Remaining'],
                    datasets: [{
                        data: [value, maxValue - value],
                        backgroundColor: [color, '#f2f2f2'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '80%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderChart('allTasksChart', allTasks, allTasks, '#007bff');
            renderChart('completedTasksChart', completedTasks, allTasks, '#28a745');
            renderChart('pendingTasksChart', pendingTasks, allTasks, '#ffc107');
            renderChart('skippedTasksChart', skippedTasks, allTasks, '#dc3545');
            renderChart('incompleteTasksChart', incompleteTasks, allTasks, '#ff6384');
        });
        
    </script>
    <script> function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("table").classList.add('menu-open'); 
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("table").classList.remove('menu-open');
        }
        </script>
</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>
