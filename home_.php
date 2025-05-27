<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Task Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            padding: 0px 10px;
        }
        .h-section h2 {
            font-size: 30px;
            font-weight: 700;
            margin: 0;
            color: #9acbe2;
        }


.header-right {
    display: flex;
    align-items: right;
  }
table {
            width: 120%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #dddddd;
            padding: 5px;
            text-align: left;
            transition: margin-left 0.5s; 
        }
        table th {
            background-color: #0F054C;
            color: white;
            padding: 3px;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #ddd;
        }
        table .button{
            width:3px;
        }
        .container {
            width: 100%; 
            margin: auto; 
            padding-top: 0px; 
            padding-bottom: 20px; 
            z-index: 0; 
        }
        .btn-small {
            padding: 2px 3px;
            font-size: 12px;
            line-height: 1.5;
        }
        .text-center {
            text-align: center;
        }
        .add-task-btn {
    font-size: 30px;
    cursor: pointer;
    color: white;
    background-color: #0F054C;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute; 
    right: 180px; 
    top: 135px; 
    z-index: 1000; 
}

.add-task-btn:hover {
    background-color: #f3f6f8;
    color: #0F054C;
}


.d-flex {
    display: flex;
    justify-content: space-between;
    align-items: left;
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
            <a href="home_.php">Home</a>
            <a href="#About Us">Profile</a>
            <a href="#absence">Mark absence</a>
            <a href="my_tasks.php">My tasks</a>
            <a href="task_calendar.php">calendar</a>
            <a href="notices.php">All notices</a>
            <a href="#badges">My Badges</a>
            <a href="#badges">Leaderboard</a>
        </nav>

        <div class="main-content" id="main">
            <section class="h-section">
                <div class="welcome-user">
                    <h2>Welcome <?php echo htmlspecialchars($employee_id); ?></h2>
                    <p>Your personal dashboard for managing tasks efficiently.</p>
                </div>
            </section>

    <div class="container">
    <h5 >Today's tasks</h5><button class="add-task-btn" onclick="showAddTaskModal()">+ </button>

            <table id="tasksTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Status Update</th>
                        <th>Comments</th>
                        <th>Update Comments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
             
    $servername = "localhost";
    $username = "velvete1_maryfabs";
    $password = "Antonmaryfabs";
    $database = "employees";
      
                $conn = new mysqli($servername, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM $employee_id WHERE DATE(start_date) <= CURDATE() AND DATE(end_date) >= CURDATE()";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['task_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['task_description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td><select class='form-control status-select' data-id='" . htmlspecialchars($row['task_id']) . "'>";
                        echo "<option value='completed'" . ($row['status'] == 'completed' ? ' selected' : '') . ">Completed</option>";
                        echo "<option value='skipped'" . ($row['status'] == 'skipped' ? ' selected' : '') . ">Skipped</option>";
                        echo "<option value='inprogress'" . ($row['status'] == 'inprogress' ? ' selected' : '') . ">In Progress</option>";
                        echo "<option value='assigned'" . ($row['status'] == 'assigned' ? ' selected' : '') . ">Assigned</option>";
                        echo "</select></td>";
                        echo "<td>" . htmlspecialchars($row['comments']) . "</td>";
                         echo "<td><textarea class='form-control' textarea id='taskComments' name='comment'></textarea></td>"; 
                        echo "<td><button class='btn btn-primary save-changes-btn' data-id='" . htmlspecialchars($row['task_id']) . "'>Save Changes</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

  <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addTaskForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                         <form id="addTaskForm" method="POST">
                                    <div class="form-group">
                                        <label for="task-title">Title</label>
                                        <input type="text" class="form-control" id="task-title" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="task-sdate">Start Date</label>
                                        <input type="date" class="form-control" id="task-sdate" name="start" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="task-edate">End Date</label>
                                        <input type="date" class="form-control" id="task-edate" name="end">
                                    </div>
                                    <div class="form-group">
        <label for="employee_select">Select Employee</label>
        <input type="text" class="form-control" id="employee" name="employee" value="<?php echo $employee_id; ?>" readonly>

    </div>
                                    <input type="hidden" id="taskEventType" name="taskEventType" value="task">
                                    <input type="hidden" id="task-id" name="id">
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
</div>

            </form>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
       
  
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
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
        function showAddTaskModal() {
            $('#addTaskModal').modal('show');
        }

        $('.save-changes-btn').on('click', function() {
            var taskId = $(this).data('id');
            var status = $(this).closest('tr').find('.status-select').val();
            var comments = $(this).closest('tr').find('.comments-textarea').val();

            $.ajax({
                url: 'update_event.php',
                type: 'POST',
                data: {
                    task_id: taskId,
                    status: status,
                    comments: comments
                },
                success: function(response) {
                    alert('Task updated successfully!');
                    location.reload(); 
                },
                error: function() {
                    alert('Error updating task.');
                }
            });
        });

        $('#addTaskForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: 'save_event.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Task added successfully!');
                    $('#addTaskModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Error adding task.');
                }
            });
        });
    </script>
</body>
</html>