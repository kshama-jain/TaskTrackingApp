<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
$servername = "localhost";
$username = "velvete1_employees";
$password = "velvetekemployees";
$database = "velvete1_employees";


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM events WHERE type = 'task' AND (start = CURDATE() OR end = CURDATE() OR CURDATE() BETWEEN start AND end  OR start>CURDATE() OR end> CURDATE())"; 

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Task Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="path/to/your/Home.css">  -->
    <style>
        #calendar {
            border: 1px solid #007bff;
            padding: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: margin-left 0.5s;
        }

        .menu-open #calendar {
            margin-left: 250px; 
        }

        .container {
            position: relative; 
            z-index: 0; 
        }

        .fc-day-grid-container .fc-day-top {
            border: 1px solid #007bff;
        }

        .fc-day-grid-event {
            padding: 5px;
            margin: 0;
            font-size: 12px;
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

.main-content {
    padding: 20px;
    transition: margin-left .5s;
}
.h-section {
    background: linear-gradient(135deg, #f9f9f9 0%, #fefefe 100%);
    text-align: center;
    padding: 40px 10px;
}
.h-section h1 {
    font-size: 48px;
    font-weight: 700;
    margin: 0;
    color: #9acbe2;
}
.h-section p {
    font-size: 15px;
    font-weight: 150;
    margin: 8px 0 20px;
    color:#9acbe2;
}
.h-image {
    width: 80%;
    max-width: 600px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.signout-btn {
    font-size: 18px;
    cursor: pointer;
    position: absolute;
    right: 20px;
    top: 20px;
    color: white; 
    background-color: transparent; 
    border: none; 
    transition: background-color 0.3s;
    padding: 8px 12px; 
  }
  
  .signout-btn:hover {
    background-color: #575757; 
  }
  
.form-group {
    margin-bottom: 20px;
}
.name-group {
    display: flex;
    justify-content: space-between;
}
.name-group input {
    flex: 1;
    padding: 10px 0;
    border: none;
    border-bottom: 1px solid #ccc;
    background: transparent;
    outline: none;
    font-size: 16px;
    transition: border-bottom-color 0.3s ease;
}
.name-group input:focus {
    border-bottom-color: #333;
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
    margin-bottom: 10px;
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
.task-cards-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap; 
}

.task-card {
  position: relative; 
  flex: 0 0 calc(33.33% - 20px);
  background-color: #97c0cd;
  border: 1px solid #a7b4e6;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
}


.task-card h3 {
  font-size: 24px;
  font-weight: 600;
  color: #333;
  margin-top: 8px; 
}

.task-card ul {
  list-style-type: none;
  padding-left: 0;
}

.task-card ul li {
  font-size: 18px;
  color: #666;
  margin-bottom: 5px;
}

.important-notices {
    background-color: white;
    color: rgb(6, 0, 0);
    padding: 90px;
    border-radius: 8px;
    margin: 0 auto; 
    max-width: 800px; 
    text-align: left; 
}
.important-notices h3 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    text-align: center;
}
.important-notices ul {
    list-style-type: none;
    padding-left: 20px;
}
.important-notices ul li {
    font-size: 18px;
    margin-bottom: 5px;
}

.header-right {
    display: flex;
    align-items: right;
  }
  .whatsapp-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: #25d366;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: background-color 0.3s;
}

.whatsapp-button:hover {
    background-color: #128c7e;
}

.whatsapp-button img {
    width: 28px;
    height: 28px;
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
            transition: margin-left 0.5s; 
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
        .container {
            width: 80%; 
            margin: auto; 
            padding-top: 0px; 
            padding-bottom: 20px; 
            z-index: 0; 
        }
    </style>
</head>
<body class="menu-open">
    <div>
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

        <div class="main-content" id="main">
        <div class="main-content" id="main">
            <div class="container">
                <h2>Assigned tasks</h2>
                <table>
                    <thead>
                        <tr>
                            <!--<th>Task ID</th>-->
                            <th>Title</th>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tasks)): ?>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                                    <td><?php echo htmlspecialchars($task['employee_id']); ?></td>
                                    <td><?php echo htmlspecialchars($task['status']); ?></td>
                                    <td><?php echo htmlspecialchars($task['start']); ?></td>
                                    <td><?php echo htmlspecialchars($task['end']); ?></td>
                                    <td><?php echo htmlspecialchars($task['comments']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No tasks found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("calendar").style.marginLeft = "250px";
        }
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("calendar").style.marginLeft = "0";
        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>