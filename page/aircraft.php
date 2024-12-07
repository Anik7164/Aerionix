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

// Insert aircraft
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $model = $conn->real_escape_string($_POST['model']);
    $capacity = $conn->real_escape_string($_POST['capacity']);
    $flight_range = $conn->real_escape_string($_POST['flight_range']);

    $result = $conn->query("SELECT MAX(aircraft_id) AS max_id FROM aircraft");
    $row = $result->fetch_assoc();
    $new_aircraft_id = $row['max_id'] + 1;

    $sql = "INSERT INTO aircraft (aircraft_id, model, capacity, flight_range) 
            VALUES ('$new_aircraft_id', '$model', '$capacity', '$flight_range')";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete aircraft
if (isset($_GET['delete'])) {
    $aircraft_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM aircraft WHERE aircraft_id=$aircraft_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update aircraft
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $aircraft_id = $conn->real_escape_string($_POST['aircraft_id']);
    $model = $conn->real_escape_string($_POST['model']);
    $capacity = $conn->real_escape_string($_POST['capacity']);
    $flight_range = $conn->real_escape_string($_POST['flight_range']);

    $sql = "UPDATE aircraft 
            SET model='$model', capacity='$capacity', flight_range='$flight_range' 
            WHERE aircraft_id='$aircraft_id'";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch aircraft for update
$update_aircraft = null;
if (isset($_GET['edit'])) {
    $aircraft_id = $conn->real_escape_string($_GET['edit']);
    $update_result = $conn->query("SELECT * FROM aircraft WHERE aircraft_id=$aircraft_id");
    $update_aircraft = $update_result->fetch_assoc();
}

// Filtering logic
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_model'])) {
        $filter_model = $conn->real_escape_string($_POST['filter_model']);
        $filterClauses[] = "model LIKE '%$filter_model%'";
    }
    if (!empty($_POST['filter_capacity_min']) || !empty($_POST['filter_capacity_max'])) {
        $capacity_min = $_POST['filter_capacity_min'] ?: 0;
        $capacity_max = $_POST['filter_capacity_max'] ?: PHP_INT_MAX;
        $filterClauses[] = "capacity BETWEEN $capacity_min AND $capacity_max";
    }
    if (!empty($_POST['filter_flight_range_min']) || !empty($_POST['filter_flight_range_max'])) {
        $flight_range_min = $_POST['filter_flight_range_min'] ?: 0;
        $flight_range_max = $_POST['filter_flight_range_max'] ?: PHP_INT_MAX;
        $filterClauses[] = "flight_range BETWEEN $flight_range_min AND $flight_range_max";
    }
}

// Sorting logic
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'aircraft_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM aircraft $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ss.css">
    <title>Aircraft Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .form-container {
            display: flex;
            gap: 20px;
            margin: 20px;
        }
        .form-container form {
            background-color: #fff;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>

<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
    <h1>Aerionix Management System</h1>
    
</header>

<main>
<button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>

    <div class="form-container">
        <form method="POST">
            <h2><?php echo $update_aircraft ? "Update Aircraft" : "Add New Aircraft"; ?></h2>
            <input type="hidden" name="aircraft_id" value="<?php echo $update_aircraft['aircraft_id'] ?? ''; ?>">
            <input type="text" name="model" placeholder="Model" value="<?php echo $update_aircraft['model'] ?? ''; ?>" required>
            <input type="number" name="capacity" placeholder="Capacity" value="<?php echo $update_aircraft['capacity'] ?? ''; ?>" required>
            <input type="number" name="flight_range" placeholder="Flight Range (km)" value="<?php echo $update_aircraft['flight_range'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo $update_aircraft ? 'update' : 'insert'; ?>">
                <?php echo $update_aircraft ? "Update Aircraft" : "Add Aircraft"; ?>
            </button>
        </form>

        <form method="POST">
            <h2>Filter Aircraft</h2>
            <input type="text" name="filter_model" placeholder="Model">
            <input type="number" name="filter_capacity_min" placeholder="Min Capacity">
            <input type="number" name="filter_capacity_max" placeholder="Max Capacity">
            <input type="number" name="filter_flight_range_min" placeholder="Min Flight Range">
            <input type="number" name="filter_flight_range_max" placeholder="Max Flight Range">
            <button type="submit" name="filter">Apply Filter</button>
        </form>
        
    </div>

    <h2>Aircraft List</h2>
    <table>
        <thead>
            <tr>
                <th><a href="?sort_column=aircraft_id&sort_order=<?php echo $next_order; ?>">Aircraft ID</a></th>
                <th><a href="?sort_column=model&sort_order=<?php echo $next_order; ?>">Model</a></th>
                <th><a href="?sort_column=capacity&sort_order=<?php echo $next_order; ?>">Capacity</a></th>
                <th><a href="?sort_column=flight_range&sort_order=<?php echo $next_order; ?>">Flight Range (km)</a></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['aircraft_id']; ?></td>
                <td><?php echo $row['model']; ?></td>
                <td><?php echo $row['capacity']; ?></td>
                <td><?php echo $row['flight_range']; ?></td>
                <td>
                    <a href="?edit=<?php echo $row['aircraft_id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $row['aircraft_id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this aircraft?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<footer>
    <p>&copy; 2024 Aerionix Management System</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
