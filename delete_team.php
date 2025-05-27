<?php
session_start();

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "Team ID not provided";
    exit();
}

$teamId = $_GET['id'];

$servername = "localhost";
$username = "velvete1_maryfabs";
$password = "Antonmaryfabs";
$database = "employees";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_fetch_team_name = "SELECT team_name FROM teams WHERE id = ?";
$stmt_fetch_team_name = $conn->prepare($sql_fetch_team_name);

if ($stmt_fetch_team_name === false) {
    echo "Error preparing select statement: " . $conn->error;
    $conn->close();
    exit();
}

$stmt_fetch_team_name->bind_param("i", $teamId);

if ($stmt_fetch_team_name->execute()) {
    $stmt_fetch_team_name->store_result();

    if ($stmt_fetch_team_name->num_rows > 0) {
        $stmt_fetch_team_name->bind_result($teamName);
        $stmt_fetch_team_name->fetch();

        $sql_drop_table = "DROP TABLE IF EXISTS `$teamName`";

        if ($conn->query($sql_drop_table) === TRUE) {
            echo "Table '{$teamName}' dropped successfully<br>";
        } else {
            echo "Error dropping table: " . $conn->error . "<br>";
        }

        $sql_update = "UPDATE employees SET team = NULL, team_id = NULL WHERE team = ?";
        $stmt_update = $conn->prepare($sql_update);

        if ($stmt_update === false) {
            echo "Error preparing update statement: " . $conn->error . "<br>";
        } else {
            $stmt_update->bind_param("s", $teamName);

            if ($stmt_update->execute()) {
                echo "Team table updated successfully<br>";
            } else {
                echo "Error updating team table: " . $stmt_update->error . "<br>";
            }

            $stmt_update->close();
        }

    } else {
        echo "Team not found<br>";
    }
} else {
    echo "Error fetching team name: " . $stmt_fetch_team_name->error . "<br>";
}

$stmt_fetch_team_name->close();

$sql_delete_team = "DELETE FROM teams WHERE id = ?";
$stmt_delete_team = $conn->prepare($sql_delete_team);

if ($stmt_delete_team === false) {
    echo "Error preparing delete statement: " . $conn->error . "<br>";
    $conn->close();
    exit();
}

$stmt_delete_team->bind_param("i", $teamId);

if ($stmt_delete_team->execute()) {
    echo "Team with ID '{$teamId}' deleted successfully<br>";
} else {
    echo "Error deleting team: " . $stmt_delete_team->error . "<br>";
}

$stmt_delete_team->close();

$conn->close();
?>
