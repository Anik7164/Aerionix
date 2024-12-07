<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aerionix";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert flight
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $departure_airport = $conn->real_escape_string($_POST['departure_airport']);
    $arrival_airport = $conn->real_escape_string($_POST['arrival_airport']);
    $departure_time = $conn->real_escape_string($_POST['departure_time']);
    $arrival_time = $conn->real_escape_string($_POST['arrival_time']);
    $aircraft_id = $conn->real_escape_string($_POST['aircraft_id']);

    $result = $conn->query("SELECT MAX(flight_id) AS max_id FROM flights");
    $row = $result->fetch_assoc();
    $new_flight_id = $row['max_id'] + 1;

    $sql = "INSERT INTO flights (flight_id, departure_airport, arrival_airport, departure_time, arrival_time, aircraft_id)
            VALUES ('$new_flight_id', '$departure_airport', '$arrival_airport', '$departure_time', '$arrival_time', '$aircraft_id')";
    $conn->query($sql);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete flight
if (isset($_GET['delete'])) {
    $flight_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM flights WHERE flight_id = $flight_id";
    $conn->query($sql);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update flight
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $departure_airport = $conn->real_escape_string($_POST['departure_airport']);
    $arrival_airport = $conn->real_escape_string($_POST['arrival_airport']);
    $departure_time = $conn->real_escape_string($_POST['departure_time']);
    $arrival_time = $conn->real_escape_string($_POST['arrival_time']);
    $aircraft_id = $conn->real_escape_string($_POST['aircraft_id']);

    $sql = "UPDATE flights SET 
                departure_airport = '$departure_airport',
                arrival_airport = '$arrival_airport',
                departure_time = '$departure_time',
                arrival_time = '$arrival_time',
                aircraft_id = '$aircraft_id'
            WHERE flight_id = $flight_id";
    $conn->query($sql);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Filtering logic
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_departure_airport'])) {
        $filter_departure_airport = $conn->real_escape_string($_POST['filter_departure_airport']);
        $filterClauses[] = "departure_airport LIKE '%$filter_departure_airport%'";
    }
    if (!empty($_POST['filter_arrival_airport'])) {
        $filter_arrival_airport = $conn->real_escape_string($_POST['filter_arrival_airport']);
        $filterClauses[] = "arrival_airport LIKE '%$filter_arrival_airport%'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'flight_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM flights $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);

$current_flight = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM flights WHERE flight_id = $edit_id");
    $current_flight = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Management</title>
    <link rel="stylesheet" href="ss.css">
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
        <h1>Flight Management</h1>
    </header>

    <main>
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <div class="form-container">
            <form method="POST">
                <h2><?php echo $current_flight ? "Update Flight" : "Add New Flight"; ?></h2>
                <input type="hidden" name="flight_id" value="<?php echo $current_flight['flight_id'] ?? ''; ?>">
                <input type="text" name="departure_airport" placeholder="Departure Airport" value="<?php echo $current_flight['departure_airport'] ?? ''; ?>" required>
                <input type="text" name="arrival_airport" placeholder="Arrival Airport" value="<?php echo $current_flight['arrival_airport'] ?? ''; ?>" required>
                <input type="datetime-local" name="departure_time" placeholder="Departure Time" value="<?php echo $current_flight['departure_time'] ?? ''; ?>" required>
                <input type="datetime-local" name="arrival_time" placeholder="Arrival Time" value="<?php echo $current_flight['arrival_time'] ?? ''; ?>" required>
                <input type="text" name="aircraft_id" placeholder="Aircraft ID" value="<?php echo $current_flight['aircraft_id'] ?? ''; ?>" required>
                <button type="submit" name="<?php echo $current_flight ? 'update' : 'insert'; ?>">
                    <?php echo $current_flight ? "Update Flight" : "Add Flight"; ?>
                </button>
            </form>

            <form method="POST">
                <h2>Filter Flights</h2>
                <input type="text" name="filter_departure_airport" placeholder="Departure Airport">
                <input type="text" name="filter_arrival_airport" placeholder="Arrival Airport">
                <button type="submit" name="filter">Apply Filter</button>
            </form>
        </div>

        <h2>Flight List</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=flight_id&sort_order=<?php echo $next_order; ?>">Flight ID</a></th>
                    <th><a href="?sort_column=departure_airport&sort_order=<?php echo $next_order; ?>">Departure Airport</a></th>
                    <th><a href="?sort_column=arrival_airport&sort_order=<?php echo $next_order; ?>">Arrival Airport</a></th>
                    <th><a href="?sort_column=departure_time&sort_order=<?php echo $next_order; ?>">Departure Time</a></th>
                    <th><a href="?sort_column=arrival_time&sort_order=<?php echo $next_order; ?>">Arrival Time</a></th>
                    <th><a href="?sort_column=aircraft_id&sort_order=<?php echo $next_order; ?>">Aircraft ID</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['flight_id']}</td>
                                <td>{$row['departure_airport']}</td>
                                <td>{$row['arrival_airport']}</td>
                                <td>{$row['departure_time']}</td>
                                <td>{$row['arrival_time']}</td>
                                <td>{$row['aircraft_id']}</td>
                                <td>
                                    <a href='?edit={$row['flight_id']}'>Edit</a> |
                                    <a href='?delete={$row['flight_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found</td></tr>";
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
