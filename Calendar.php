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
                <a href="home.php">Home</a>
                <a href="#About Us">Employee List</a>
                <a href="#badges">Register new team</a>
                <a href="register_emp.php">Register new employee</a>
                <a href="Calendar.php">calendar</a>
                <a href="notice.php">All notices</a>
                <a href="#badges">Remove team</a>
                <a href="#badges">Remove employee</a>
            </nav>

        <div class="container">
            <div id="calendar"></div>
        </div>
            <div class="modal fade" id="taskEventModal" tabindex="-1" role="dialog" aria-labelledby="taskEventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskEventModalLabel">Add Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="taskEventTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="add-event-tab" data-toggle="tab" href="#add-event" role="tab" aria-controls="add-event" aria-selected="true" onclick="setTaskEventType('event')">Add Event</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="add-task-tab" data-toggle="tab" href="#add-task" role="tab" aria-controls="add-task" aria-selected="false" onclick="setTaskEventType('task')">Add Task</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="taskEventTabContent">
                            <div class="tab-pane fade show active" id="add-event" role="tabpanel" aria-labelledby="add-event-tab">
                                <form id="addEventForm" method="POST">
                                    <div class="form-group">
                                        <label for="event-title">Title</label>
                                        <input type="text" class="form-control" id="event-title" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="event-start">Start Date</label>
                                        <input type="date" class="form-control" id="event-start" name="start" required>
                                    </div>

                                    <input type="hidden" id="taskEventType" name="taskEventType" value="event">
                                    <input type="hidden" id="event-id" name="id">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                         <button type="button" class="btn btn-danger" onclick="deleteEvent()">Delete</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            <label for="team_select">Select Team</label>
            <select class="form-control" id="team_select" name="team" onchange="updateEmployeeDropdown()">
            </select>
        </div>
        <div class="form-group">
            <label for="employee_select">Select Employee</label>
            <select class="form-control" id="employee_select" name="employee">
            </select>
        </div>
        <input type="hidden" id="taskEventType" name="taskEventType" value="task">
        <input type="hidden" id="task-id" name="id">
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" onclick="deleteEvent()">Delete</button>
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
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("calendar").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("calendar").style.marginLeft = "0";
        }
        
        $(document).ready(function() {
    $.ajax({
        url: 'fetch_teams.php', 
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var teamDropdown = $('#team_select');
            teamDropdown.empty();
            
            teamDropdown.append($('<option></option>').attr('value', '').text('Select a team'));
            $.each(data, function(index, team) {
                teamDropdown.append($('<option></option>').attr('value', team.team_name).text(team.team_name));
            });
        },
        error: function() {
            alert('There was an error fetching teams!');
        }
    });
    $('#team_select').change(function() {
        var selectedTeam = $(this).val();
        $.ajax({
            url: 'fetch_employees1.php',
            type: 'GET',
            data: { team: selectedTeam },
            dataType: 'json',
            success: function(data) {
                var employeeDropdown = $('#employee_select');
                employeeDropdown.empty();
                employeeDropdown.append($('<option></option>').attr('value', '').text('All employees'));
                $.each(data, function(index, employees) {
                    employeeDropdown.append($('<option></option>').attr('value', employees.employee_id).text(employees.employee_id));
                });
            },
            error: function() {
                alert('There was an error fetching employees!');
            }
        });
    });

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            views: {
                week: {
                    titleFormat: 'MMM D YYYY',
                    columnHeaderFormat: 'ddd D'
                },
                day: {
                    titleFormat: 'MMMM D YYYY',
                    columnHeaderFormat: 'dddd'
                }
            },
            dayClick: function(date, jsEvent, view) {
                         
            $('#addEventForm')[0].reset();
            $('#addTaskForm')[0].reset();
            $('#event-id').val('');
            $('#task-id').val('');
                $('#event-start').val(date.format('YYYY-MM-DD'));
                $('#task-sdate').val(date.format('YYYY-MM-DD'));
                $('#taskEventModal').modal('show');
            },
            events: {
                url: 'fetch_events.php',
                type: 'GET',
                error: function() {
                    alert('There was an error while fetching events!');
                },
                success: function(events) {

                    $.each(events, function(index, event) {
                        if (event.type === 'event') {
                            event.color = 'green';
                        } else if (event.type === 'task') {
                            event.color = 'blue';
                            event.assigned_to = event.employee_id;
                        }
                    });
                }
            },
            eventRender: function(event, element) {
                var title = event.title ;
                element.find('.fc-title').html(title);
                if (event.type === 'task') {
                    element.find('.fc-title').append('<br/><small>Assigned to: ' + event.assigned_to + '</small>');
                }
            },
            eventClick: function(event, jsEvent, view) {
                
            $('#taskEventModalLabel').text(event.type === 'event' ? 'Edit Event' : 'Edit Task');
                    if (event.type === 'event') {
                        $('#taskEventTabs a[href="#add-event"]').tab('show');
                        $('#event-title').val(event.title);
                        $('#event-start').val(event.start.format('YYYY-MM-DD'));
                        $('#event-id').val(event.id);
                        $('#taskEventType').val('event');
                    } else if (event.type === 'task') {
                        $('#taskEventTabs a[href="#add-task"]').tab('show');
                        $('#task-title').val(event.title);
                        $('#task-sdate').val(event.start.format('YYYY-MM-DD'));
                        $('#task-edate').val(event.end ? event.end.format('YYYY-MM-DD') : '');
                        $('#task-id').val(event.id);
                        $('#taskEventType').val('task');
                        $('#employee_select').val(event.assigned_to);
                    }

            $('#taskEventModal').modal('show');
            
            $('.btn-danger').off().on('click', function() {
                    if (confirm("Are you sure you want to delete this event/task?")) {
                        $.ajax({
                            url: 'delete_event.php',
                            type: 'POST',
                            data: { id: event.id, type: event.type },
                            success: function(response) {
                                $('#calendar').fullCalendar('refetchEvents');
                                $('#taskEventModal').modal('hide');
                                alert('Event/task deleted successfully');
                            },
                            error: function() {
                                alert('There was an error deleting the event/task');
                            }
            });
            
        }
    }); 
} 
            
        });

        $('#addEventForm').submit(function(event) {
            event.preventDefault();
            var assignAll = $('#assign_all_employees').is(':checked');
        
        var formData = $(this).serialize();
        formData += '&assign_all=' + (assignAll ? '1' : '0');
        
            $.ajax({
                url: 'save_event.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#taskEventModal').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                    alert('Event added successfully');
                },
                error: function() {
                    alert('There was an error saving the event');
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

    </script>


    </body>
    </html>


        

