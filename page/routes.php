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

// Insert route
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $departure = $conn->real_escape_string($_POST['departure_airport_id']);
    $arrival = $conn->real_escape_string($_POST['arrival_airport_id']);
    $distance = $conn->real_escape_string($_POST['distance']);
    $result = $conn->query("SELECT MAX(route_id) AS max_id FROM routes");
    $row = $result->fetch_assoc();
    $new_route_id = $row['max_id'] + 1;

    $sql = "INSERT INTO routes (route_id,departure_airport_id, arrival_airport_id, distance) VALUES ('$new_route_id','$departure', '$arrival', '$distance')";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete route
if (isset($_GET['delete'])) {
    $route_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM routes WHERE route_id = $route_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update route
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $route_id = $conn->real_escape_string($_POST['route_id']);
    $departure = $conn->real_escape_string($_POST['departure_airport_id']);
    $arrival = $conn->real_escape_string($_POST['arrival_airport_id']);
    $distance = $conn->real_escape_string($_POST['distance']);
    
    $sql = "UPDATE routes SET departure_airport_id = '$departure', arrival_airport_id = '$arrival', distance = '$distance' WHERE route_id = $route_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Filtering and sorting logic
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_departure'])) {
        $filter_departure = $conn->real_escape_string($_POST['filter_departure']);
        $filterClauses[] = "departure_airport_id = '$filter_departure'";
    }
    if (!empty($_POST['filter_arrival'])) {
        $filter_arrival = $conn->real_escape_string($_POST['filter_arrival']);
        $filterClauses[] = "arrival_airport_id = '$filter_arrival'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'route_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM routes $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);
$current_route = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM routes WHERE route_id = $edit_id");
    $current_route = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Management</title>
    <link rel="stylesheet" href="ss.css">
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
    <h1>Route Management</h1>
</header>

<main>
    <div class="form-container">
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <form method="POST">
            <h2><?php echo $current_route ? "Update Route" : "Add New Route"; ?></h2>
            <input type="hidden" name="route_id" value="<?php echo $current_route['route_id'] ?? ''; ?>">
            <input type="text" name="departure_airport_id" placeholder="Departure Airport ID" value="<?php echo $current_route['departure_airport_id'] ?? ''; ?>" required>
            <input type="text" name="arrival_airport_id" placeholder="Arrival Airport ID" value="<?php echo $current_route['arrival_airport_id'] ?? ''; ?>" required>
            <input type="number" name="distance" placeholder="Distance (km)" value="<?php echo $current_route['distance'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo $current_route ? 'update' : 'insert'; ?>">
                <?php echo $current_route ? "Update Route" : "Add Route"; ?>
            </button>
        </form>

        <!-- Filter Form -->
        <form method="POST">
            <h2>Filter Routes</h2>
            <input type="text" name="filter_departure" placeholder="Departure Airport ID">
            <input type="text" name="filter_arrival" placeholder="Arrival Airport ID">
            <button type="submit" name="filter">Apply Filter</button>
        </form>
    </div>

    <h2>Route List</h2>
    <table>
        <thead>
            <tr>
                <th><a href="?sort_column=route_id&sort_order=<?php echo $next_order; ?>">Route ID</a></th>
                <th><a href="?sort_column=departure_airport_id&sort_order=<?php echo $next_order; ?>">Departure Airport</a></th>
                <th><a href="?sort_column=arrival_airport_id&sort_order=<?php echo $next_order; ?>">Arrival Airport</a></th>
                <th><a href="?sort_column=distance&sort_order=<?php echo $next_order; ?>">Distance (km)</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['route_id']}</td>
                            <td>{$row['departure_airport_id']}</td>
                            <td>{$row['arrival_airport_id']}</td>
                            <td>{$row['distance']}</td>
                            <td>
                                <a href='?edit={$row['route_id']}'>Edit</a> |
                                <a href='?delete={$row['route_id']}' onclick='return confirm(\"Are you sure you want to delete this route?\")'>Delete</a>
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
