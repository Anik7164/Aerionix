<?php
// checkin.php

// Database connection
$servername = "localhost"; // Change if necessary
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "aerionix"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch check-in data
$sql = "SELECT * FROM checkin";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Table</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            background-image: url('checkin.jpg'); /* Update with your image path */
            background-size:cover ; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the background image */
            background-repeat: no-repeat; /* Prevents the image from repeating */
          }
    </style>
</head>
<body>
    <header>
        <h1>Check-in Table</h1>
    </header>

    <main>
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <table>
            <thead>
                <tr>
                    <th>Check-in ID</th>
                    <th>Flight ID</th>
                    <th>Passenger ID</th>
                    <th>Check-in Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['checkin_id']}</td>
                                <td>{$row['flight_id']}</td>
                                <td>{$row['passenger_id']}</td>
                                <td>{$row['checkin_time']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Aerionix Project. All rights reserved.</p>
    </footer>
</body>
</html>