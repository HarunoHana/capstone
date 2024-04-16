<?php
$servername = "db.luddy.indiana.edu";
$username = "i494f23_team04";
$password = "my+sql=i494f23_team04";
$dbname = "i494f23_team04";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $app_id = $_GET['id'];

    $sql_delete_schedule = "DELETE FROM schedule WHERE schedule_id = $schedule_id";

    if ($conn->query($sql_delete_schedule) === FALSE) {
        echo "<script>alert('Error deleting related schedule records: " . $conn->error . "'); window.location.href='admin.php';</script>";
        exit();
    }

    $sql_delete_links = "DELETE FROM appointments WHERE app_id = $app_id";

    if ($conn->query($sql_delete_links) === TRUE) {
        echo "<script>alert('Record deleted successfully'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "'); window.location.href='admin.php';</script>";
    }
} else {
    echo "<script>alert('Invalid ID parameter'); window.location.href='admin.php';</script>";
}

$conn->close();
?>
