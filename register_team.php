<?php
session_start();
if ($_SESSION['role'] != 'admin') {
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
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teamName = $_POST['teamName'];
    $projectName = $_POST['projectName'];

    if (empty($teamName) || empty($projectName)) {
        die("Please fill all required fields.");
    }

    $servername = "localhost";
    $username = "velvete1_maryfabs";
    $password = "Antonmaryfabs";
    $database = "employees";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO teams (team_id, team_name, project_name) 
            SELECT CONCAT('T', MAX(CAST(SUBSTRING(team_id, 2) AS UNSIGNED)) + 1), '$teamName', '$projectName' 
            FROM teams";
    if ($conn->query($sql) === TRUE) {
        echo "Team registered successfully!";
    } else {
        if ($conn->errno == 1062) {
            echo "Error: Duplicate entry. This team might already exist.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $createTableSql = "CREATE TABLE `$teamName` (
        task_description text NOT NULL,
        task_id int(11) NOT NULL,
        start_date date DEFAULT NULL,
        end_date date DEFAULT NULL,
        status varchar(50) DEFAULT NULL,
        comments text DEFAULT NULL
    )";

    if ($conn->query($createTableSql) === TRUE) {
        echo "Table $teamName created successfully!";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Form not submitted.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Team</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0px;
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
            <a href="Calendar.php">calendar</a>
            <a href="notice.php">All notices</a>
            <a href="#badges">Remove team</a>
            <a href="#badges">Remove employee</a>
        </nav>
    <div class="container">
        <h2>Register New Team</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="teamName">Team Name:</label>
                    <input type="text" class="form-control" id="teamName" name="teamName" required>
                </div>
                <div class="form-group">
                    <label for="projectName">Project Name:</label>
                    <input type="text" class="form-control" id="projectName" name="projectName" required>
                </div>
                <button type="submit" class="btn btn-primary">Register Team</button>
                <a href="modify_teams.php" class="btn btn-info">Modify Existing Teams</a>
            </form>
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
</html>
