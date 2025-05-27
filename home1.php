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
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #dddddd;
            padding: 1px;
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
            width: 80%; 
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
                    <th>Comments</th>
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

                $sql = "SELECT * FROM $employee_id ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr data-toggle='modal' data-target='#taskModal' data-id='" . htmlspecialchars($row['task_id']) . "' data-title='" . htmlspecialchars($row['task_description']) . "' data-start='" . htmlspecialchars($row['start_date']) . "' data-end='" . htmlspecialchars($row['end_date']) . "' data-status='" . htmlspecialchars($row['status']) . "' data-comments='" . htmlspecialchars($row['comments']) . "'>";
                    echo "<td>" . htmlspecialchars($row['task_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['task_description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['comments']) . "</td>";
                    echo "</tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    </div>

    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="taskForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="task-id" name="task_id">
                    <p><strong>Title:</strong> <span id="task-title"></span></p>
                    <p><strong>Completed:</strong> <input type="radio" name="status" id="completed" class="status-radio" value="completed"></p>
                    <p><strong>Skipped:</strong> <input type="radio" name="status" id="skipped" class="status-radio" value="skipped"></p>
                    <p><strong>In Progress:</strong> <input type="radio" name="status" id="inprogress" class="status-radio" value="inprogress"></p>
                    <p><strong>Assigned:</strong> <input type="radio" name="status" id="assigned" class="status-radio" value="assigned"></p>
                    <p><strong>Comments:</strong> <textarea id="taskComments" class="form-control" name="comment"></textarea></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
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
                        <label for="taskTitle">Title</label>
                        <input type="text" class="form-control" id="taskTitle" name="taskTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="taskStartDate">Start Date</label>
                        <input type="date" class="form-control" id="taskStartDate" name="taskStartDate" required>
                    </div>
                    <div class="form-group">
                        <label for="taskEndDate">End Date</label>
                        <input type="date" class="form-control" id="taskEndDate" name="taskEndDate" >
                    </div>
                    <div class="form-group">
                        <label for="taskComments">Comments</label>
                        <textarea class="form-control" id="taskComments" name="taskComments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
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
    $('#taskModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var id = button.data('id');
        var title = button.data('title');
        var status = button.data('status');
        var comments = button.data('comments');

        var modal = $(this);
        modal.find('#task-id').val(id);
        modal.find('#task-title').text(title);
        modal.find('#taskComments').val(comments);
        modal.find('.status-radio').prop('checked', false);
        modal.find('.status-radio[value="' + status + '"]').prop('checked', true);
    });

    $('#taskForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'update_event.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert('Task updated successfully!');
                $('#taskModal').modal('hide');
                location.reload();  
            },
            error: function() {
                alert('Error updating task.');
            }
        });
    });
    function showAddTaskModal() {
    $('#addTaskModal').modal('show');
}

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
