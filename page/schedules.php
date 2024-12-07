<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "aerionix"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert schedule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $departure_time = $conn->real_escape_string($_POST['departure_time']);
    $arrival_time = $conn->real_escape_string($_POST['arrival_time']);
    
    $sql = "INSERT INTO schedules (flight_id, departure_time, arrival_time) VALUES ('$flight_id', '$departure_time', '$arrival_time')";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete schedule
if (isset($_GET['delete'])) {
    $schedule_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM schedules WHERE schedule_id = $schedule_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update schedule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $schedule_id = $conn->real_escape_string($_POST['schedule_id']);
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $departure_time = $conn->real_escape_string($_POST['departure_time']);
    $arrival_time = $conn->real_escape_string($_POST['arrival_time']);
    
    $sql = "UPDATE schedules SET flight_id = '$flight_id', departure_time = '$departure_time', arrival_time = '$arrival_time' WHERE schedule_id = $schedule_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Filtering and sorting logic
$filterClauses = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
    if (!empty($_POST['filter_flight_id'])) {
        $filter_flight_id = $conn->real_escape_string($_POST['filter_flight_id']);
        $filterClauses[] = "flight_id = '$filter_flight_id'";
    }
    if (!empty($_POST['filter_departure_time'])) {
        $filter_departure_time = $conn->real_escape_string($_POST['filter_departure_time']);
        $filterClauses[] = "departure_time >= '$filter_departure_time'";
    }
    if (!empty($_POST['filter_arrival_time'])) {
        $filter_arrival_time = $conn->real_escape_string($_POST['filter_arrival_time']);
        $filterClauses[] = "arrival_time <= '$filter_arrival_time'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'schedule_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM schedules $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);
$current_schedule = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM schedules WHERE schedule_id = $edit_id");
    $current_schedule = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Schedule Management</title>
    <link rel="stylesheet" href="ss.css">
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
    <h1>Flight Schedule Management</h1>
</header>

<main>
    <div class="form-container">
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>

        <form method="POST">
            <h2><?php echo $current_schedule ? "Update Schedule" : "Add New Schedule"; ?></h2>
            <input type="hidden" name="schedule_id" value="<?php echo $current_schedule['schedule_id'] ?? ''; ?>">
            <input type="text" name="flight_id" placeholder="Flight ID" value="<?php echo $current_schedule['flight_id'] ?? ''; ?>" required>
            <input type="datetime-local" name="departure_time" placeholder="Departure Time" value="<?php echo isset($current_schedule['departure_time']) ? date('Y-m-d\TH:i', strtotime($current_schedule['departure_time'])) : ''; ?>" required>
            <input type="datetime-local" name="arrival_time" placeholder="Arrival Time" value="<?php echo isset($current_schedule['arrival_time']) ? date('Y-m-d\TH:i', strtotime($current_schedule['arrival_time'])) : ''; ?>" required>
            <button type="submit" name="<?php echo $current_schedule ? 'update' : 'insert'; ?>">
                <?php echo $current_schedule ? "Update Schedule" : "Add Schedule"; ?>
            </button>
        </form>

        <!-- Filter Form -->
        <form method="POST">
            <h2>Filter Schedules</h2>
            <input type="text" name="filter_flight_id" placeholder="Flight ID">
            <input type="datetime-local" name="filter_departure_time" placeholder="Departure Time">
            <input type="datetime-local" name="filter_arrival_time" placeholder="Arrival Time">
            <button type="submit" name="filter">Apply Filter</button>
        </form>
    </div>

    <h2>Schedule List</h2>
    <table>
        <thead>
            <tr>
                <th><a href="?sort_column=schedule_id&sort_order=<?php echo $next_order; ?>">Schedule ID</a></th>
                <th><a href="?sort_column=flight_id&sort_order=<?php echo $next_order; ?>">Flight ID</a></th>
                <th><a href="?sort_column=departure_time&sort_order=<?php echo $next_order; ?>">Departure Time</a></th>
                <th><a href="?sort_column=arrival_time&sort_order=<?php echo $next_order; ?>">Arrival Time</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['schedule_id']}</td>
                            <td>{$row['flight_id']}</td>
                            <td>{$row['departure_time']}</td>
                            <td>{$row['arrival_time']}</td>
                            <td>
                                <a href='?edit={$row['schedule_id']}'>Edit</a> |
                                <a href='?delete={$row['schedule_id']}' onclick='return confirm(\"Are you sure you want to delete this schedule?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<footer>
    <p>&copy; 2024 Aerionix Project. All rights reserved.</p>
</footer>
</body>
</html>
