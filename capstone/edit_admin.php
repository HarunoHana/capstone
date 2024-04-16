<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="images/iu.png">
    <style>
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #333;
            color: white;
            cursor: pointer;
            border: none;
        }
    </style>
</head>

<body>
    <header>
        <img src="images/log.jpg" alt="Image Description" id="header-image">
        <h1>IU Crimson Cupboard</h1>
        
    </header>

    <section>
        <h2>Edit Appointment</h2>
        <a href="admin.php?" class="btn btn-outline-primary btn-sm">Back</a>
        <br><br>
        <?php
        // connect to database
        $servername = "db.luddy.indiana.edu";
        $username = "i494f23_team04";
        $password = "my+sql=i494f23_team04";
        $dbname = "i494f23_team04";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $app_id = $_POST["app_id"];
            $new_date = $_POST["new_date"];
            $new_time = $_POST["new_time"];
            $new_status = $_POST["new_status"];

            // update
            $update_sql = "UPDATE appointments SET date='$new_date', time='$new_time', status='$new_status' WHERE app_id=$app_id";

            if ($conn->query($update_sql) === TRUE) {
                echo "<script>alert('Updated successfully'); window.location.href='admin.php';</script>";
            } else {
                echo "Error: " . $conn->error;
            }
        }


        if (isset($_GET["id"])) {
            $app_id = $_GET["id"];
            $select_sql = "SELECT * FROM appointments WHERE app_id=$app_id";
            $result = $conn->query($select_sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
        ?>

                <form method="post" action="">
                    <input type="hidden" name="app_id" value="<?php echo $row['app_id']; ?>">

                    <label>Date:</label>
                    <input type="text" name="new_date" value="<?php echo $row['date']; ?>"><br>

                    <label>Time:</label>
                    <input type="text" name="new_time" value="<?php echo $row['time']; ?>"><br>

                    <label>Status:</label>
                    <input type="text" name="new_status" value="<?php echo $row['status']; ?>"><br>

                    <input type="submit" value="Save Changes">
                </form>
        <?php
            } else {
                echo "No data found for this ID.";
            }
        } else {
            echo "No ID specified.";
        }

        // Close connection
        $conn->close();
        ?>
    </section>

    <footer>
        &copy; FA23 Capstone Team 04
    </footer>
</body>

</html>
