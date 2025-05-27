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
    <title>Calendar with Tasks and Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css' rel='stylesheet' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js'></script>

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
            border: 1px solid #007bff; /* Blue border for each date cell */
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

        .nav-tabs .nav-link {
            font-size: 14px;
        }

        .tab-content {
            padding-top: 10px;
        }
    </style>
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
        <div id="calendar"></div>
    </div>
    
    <div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskDetailsModalLabel">Task Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="taskDetailsTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="status" aria-selected="false">Status</a>
                        </li>
                       <li class="nav-item">
                                <a class="nav-link" id="add-task-tab" data-toggle="tab" href="#add-task" role="tab" aria-controls="add-task" aria-selected="false" onclick="setTaskEventType('task')">Add New Task</a>
                            </li>
                    </ul>
                    <div class="tab-content" id="taskDetailsTabContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <p><strong>Title:</strong> <span class="taskTitle"></span></p>
                            <p><strong>Start:</strong> <span id="taskStart"></span></p>
                            <p><strong>End:</strong> <span id="taskEnd"></span></p>
                        </div>
                        <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
                             <form id="updateTaskForm" method="POST">
                            <p><strong>Title:</strong><input type="text" name='title'  class="taskTitle" readonly></p>
    <p><strong>Completed:</strong> <input type="radio" name="status" id="completed" class="status-radio" value="completed"></p>
<p><strong>Skipped:</strong> <input type="radio" name="status" id="skipped" class="status-radio" value="skipped"></p>
<p><strong>In Progress:</strong> <input type="radio" name="status" id="inprogress" class="status-radio" value="inprogress"></p>
<p><strong>Assigned</strong> <input type="radio" name="status" id="assigned" class="status-radio" value="assigned"></p>
    <p><strong>Comments:</strong> <textarea id="taskComments" class="form-control" name="comment"></textarea></p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveTaskDetails">Save changes</button>
                </div>
                </form>
</div>

                        <div class="tab-pane fade" id="add-task" role="tabpanel" aria-labelledby="add-task-tab">
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
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: {
            url: 'fetch_tasks.php',
            type: 'GET',
        },
        eventRender: function(event, element) {
            element.on('click', function() {
                console.log("Event clicked:", event);
                $('.taskTitle').text(event.title);
                $('.taskTitle').val(event.title);
                $('#taskStart').text(event.start.format('YYYY-MM-DD HH:mm:ss'));
                $('#taskEnd').text(event.end ? event.end.format('YYYY-MM-DD HH:mm:ss') : '');
                $('input[name="status"][value="' + event.status + '"]').prop('checked', true);
                $('#taskComments').val(event.comments);
                $('#taskId').val(event.id); 

                $('#taskDetailsModal').modal('show');
            });
        }
    });
    $('#updateTaskForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'update_event.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#taskEventModal').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                    alert(response);
                },
                error: function(response) {
                    alert("There was an error saving the event");
                }
            });
        });
        $('#addTaskForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'save_event.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#taskEventModal').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                    alert('Task added successfully');
                },
                error: function() {
                    alert('There was an error saving the task');
                }
            });
        });

});
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("calendar").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("calendar").style.marginLeft = "0";
    }


    </script>
</body>
</html>
