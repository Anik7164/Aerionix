<?php
// flight_stats.php

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

// Sorting Logic
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'flight_stats_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

// Insert flight stats
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $departure_delay = $conn->real_escape_string($_POST['departure_delay']);
    $arrival_delay = $conn->real_escape_string($_POST['arrival_delay']);
    $flight_duration = $conn->real_escape_string($_POST['flight_duration']);

    $result = $conn->query("SELECT MAX(flight_stats_id) AS max_id FROM flight_stats");
    $row = $result->fetch_assoc();
    $new_id = $row['max_id'] + 1;

    $sql = "INSERT INTO flight_stats (flight_stats_id, flight_id, departure_delay, arrival_delay, flight_duration) 
            VALUES ('$new_id', '$flight_id', '$departure_delay', '$arrival_delay', '$flight_duration')";
    $conn->query($sql);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete flight stats
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM flight_stats WHERE flight_stats_id = $id";
    $conn->query($sql);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update flight stats
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $conn->real_escape_string($_POST['flight_stats_id']);
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $departure_delay = $conn->real_escape_string($_POST['departure_delay']);
    $arrival_delay = $conn->real_escape_string($_POST['arrival_delay']);
    $flight_duration = $conn->real_escape_string($_POST['flight_duration']);

    $sql = "UPDATE flight_stats 
            SET flight_id = '$flight_id', 
                departure_delay = '$departure_delay', 
                arrival_delay = '$arrival_delay', 
                flight_duration = '$flight_duration' 
            WHERE flight_stats_id = $id";
    $conn->query($sql);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch data for display
$sql = "SELECT * FROM flight_stats ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

// Handle edit state
$current_stats = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM flight_stats WHERE flight_stats_id = $edit_id");
    $current_stats = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Statistics Table</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            background-image: url('flight_stats.jpg'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
        }
    </style>
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
        <h1>Flight Statistics Management</h1>
    </header>

    <main>
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <div class="form-container">
            <form method="POST">
                <h2><?php echo $current_stats ? "Update Flight Stats" : "Add New Flight Stats"; ?></h2>
                <input type="hidden" name="flight_stats_id" value="<?php echo $current_stats['flight_stats_id'] ?? ''; ?>">
                <input type="text" name="flight_id" placeholder="Flight ID" value="<?php echo $current_stats['flight_id'] ?? ''; ?>" required>
                <input type="number" name="departure_delay" placeholder="Departure Delay (minutes)" value="<?php echo $current_stats['departure_delay'] ?? ''; ?>" required>
                <input type="number" name="arrival_delay" placeholder="Arrival Delay (minutes)" value="<?php echo $current_stats['arrival_delay'] ?? ''; ?>" required>
                <input type="number" step="0.1" name="flight_duration" placeholder="Flight Duration (hours)" value="<?php echo $current_stats['flight_duration'] ?? ''; ?>" required>
                <button type="submit" name="<?php echo $current_stats ? 'update' : 'insert'; ?>">
                    <?php echo $current_stats ? "Update Stats" : "Add Stats"; ?>
                </button>
            </form>
        </div>

        <!-- Table for Display -->
        <h2>Flight Statistics List</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=flight_stats_id&sort_order=<?php echo $next_order; ?>">Flight Stats ID</a></th>
                    <th><a href="?sort_column=flight_id&sort_order=<?php echo $next_order; ?>">Flight ID</a></th>
                    <th><a href="?sort_column=departure_delay&sort_order=<?php echo $next_order; ?>">Departure Delay (minutes)</a></th>
                    <th><a href="?sort_column=arrival_delay&sort_order=<?php echo $next_order; ?>">Arrival Delay (minutes)</a></th>
                    <th><a href="?sort_column=flight_duration&sort_order=<?php echo $next_order; ?>">Flight Duration (hours)</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['flight_stats_id']}</td>
                                <td>{$row['flight_id']}</td>
                                <td>{$row['departure_delay']}</td>
                                <td>{$row['arrival_delay']}</td>
                                <td>{$row['flight_duration']}</td>
                                <td>
                                    <a href='?edit={$row['flight_stats_id']}'>Edit</a> |
                                    <a href='?delete={$row['flight_stats_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
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

<?php $conn->close(); ?>
