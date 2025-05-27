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
    <title>Team List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
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
    h1 {
        text-align: center;
        color: #333;
    }
    .notice {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f9f9f9;
        border-left: 5px solid #3498db;
        border-radius: 3px;
    }
    .notice-title {
        font-weight: bold;
    }
    .notice-date {
        color: #777;
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
<body class="menu-open">
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
        <h1>Team List</h1>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Team Name</th>
                    <th scope="col">No. of Employees</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
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

                $sql = "SELECT team_name, id, (SELECT COUNT(*) FROM employees WHERE employees.team_id = teams.team_id) AS employee_count, status FROM teams";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["team_name"] . "</td>";
                        echo "<td>" . $row["employee_count"] . "</td>";
                        echo "<td><span class='badge badge-" . ($row["status"] == 'active' ? 'success' : 'secondary') . "'><i class='fa fa-check-circle'></i> " . ucfirst($row["status"]) . "</span></td>";
                        echo "<td>
                        <button class='btn btn-primary btn-sm' onclick=\"location.href='edit.php?id=" . $row["id"] . "'\">Edit</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteTeam(" . $row["id"] . ")'>Delete</button>
                      </td>";
                echo "</tr>";
                
                    }
                } else {
                    echo "<tr><td colspan='4'>No teams available</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        function deleteTeam(teamId) {
            if (confirm('Are you sure you want to delete this team?')) {
                $.ajax({
                    url: 'delete_team.php?id=' + teamId,
                    type: 'POST',
                    success: function(response) {
                        alert(response); 
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting team');
                        console.error(xhr.responseText);
                    }
                });
            }
        }
    </script>
</body>
</html>
