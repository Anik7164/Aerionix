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

// Insert baggage
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $passenger_id = $conn->real_escape_string($_POST['passenger_id']);
    $baggage_type = $conn->real_escape_string($_POST['baggage_type']);

    $result = $conn->query("SELECT MAX(baggage_id) AS max_id FROM baggage");
    $row = $result->fetch_assoc();
    $new_baggage_id = $row['max_id'] + 1;

    $sql = "INSERT INTO baggage (baggage_id, flight_id, passenger_id, baggage_type) VALUES ('$new_baggage_id', '$flight_id', '$passenger_id', '$baggage_type')";
    $conn->query($sql);
}

// Delete baggage
if (isset($_GET['delete'])) {
    $baggage_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM baggage WHERE baggage_id = $baggage_id";
    $conn->query($sql);
}

// Update baggage
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $baggage_id = $conn->real_escape_string($_POST['baggage_id']);
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $passenger_id = $conn->real_escape_string($_POST['passenger_id']);
    $baggage_type = $conn->real_escape_string($_POST['baggage_type']);

    $sql = "UPDATE baggage SET flight_id = '$flight_id', passenger_id = '$passenger_id', baggage_type = '$baggage_type' WHERE baggage_id = $baggage_id";
    $conn->query($sql);
}

// Filtering logic and sorting logic
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_flight_id'])) {
        $filter_flight_id = $conn->real_escape_string($_POST['filter_flight_id']);
        $filterClauses[] = "flight_id LIKE '%$filter_flight_id%'";
    }
    if (!empty($_POST['filter_passenger_id'])) {
        $filter_passenger_id = $conn->real_escape_string($_POST['filter_passenger_id']);
        $filterClauses[] = "passenger_id LIKE '%$filter_passenger_id%'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'baggage_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM baggage $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);

$current_baggage = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM baggage WHERE baggage_id = $edit_id");
    $current_baggage = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baggage Management</title>
    <link rel="stylesheet" href="ss.css">
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
        <h1>Baggage Management</h1>
    </header>

    <main>
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <div class="form-container">
            <form method="POST">
                <h2><?php echo $current_baggage ? "Update Baggage" : "Add New Baggage"; ?></h2>
                <input type="hidden" name="baggage_id" value="<?php echo $current_baggage['baggage_id'] ?? ''; ?>">
                <input type="text" name="flight_id" placeholder="Flight ID" value="<?php echo $current_baggage['flight_id'] ?? ''; ?>" required>
                <input type="text" name="passenger_id" placeholder="Passenger ID" value="<?php echo $current_baggage['passenger_id'] ?? ''; ?>" required>
                <input type="text" name="baggage_type" placeholder="Baggage Type" value="<?php echo $current_baggage['baggage_type'] ?? ''; ?>" required>
                <button type="submit" name="<?php echo $current_baggage ? 'update' : 'insert'; ?>">
                    <?php echo $current_baggage ? "Update Baggage" : "Add Baggage"; ?>
                </button>
            </form>

            <form method="POST">
                <h2>Filter Baggage</h2>
                <input type="text" name="filter_flight_id" placeholder="Flight ID">
                <input type="text" name="filter_passenger_id" placeholder="Passenger ID">
                <button type="submit" name="filter">Apply Filter</button>
            </form>
        </div>

        <h2>Baggage List</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=baggage_id&sort_order=<?php echo $next_order; ?>">Baggage ID</a></th>
                    <th><a href="?sort_column=flight_id&sort_order=<?php echo $next_order; ?>">Flight ID</a></th>
                    <th><a href="?sort_column=passenger_id&sort_order=<?php echo $next_order; ?>">Passenger ID</a></th>
                    <th><a href="?sort_column=baggage_type&sort_order=<?php echo $next_order; ?>">Baggage Type</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['baggage_id']}</td>
                                <td>{$row['flight_id']}</td>
                                <td>{$row['passenger_id']}</td>
                                <td>{$row['baggage_type']}</td>
                                <td>
                                    <a href='?edit={$row['baggage_id']}'>Edit</a> |
                                    <a href='?delete={$row['baggage_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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

<?php $conn->close(); ?>
