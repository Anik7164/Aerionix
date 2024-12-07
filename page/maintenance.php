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

// Insert maintenance record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $aircraft_id = $conn->real_escape_string($_POST['aircraft_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);
    $result = $conn->query("SELECT MAX(maintenance_id) AS max_id FROM maintenance");
    $row = $result->fetch_assoc();
    $new_maintenance_id = $row['max_id'] + 1;

    $sql = "INSERT INTO maintenance (maintenance_id, aircraft_id, date, description) VALUES ('$new_maintenance_id', '$aircraft_id', '$date', '$description')";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update maintenance record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $maintenance_id = $conn->real_escape_string($_POST['maintenance_id']);
    $aircraft_id = $conn->real_escape_string($_POST['aircraft_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);
    
    $sql = "UPDATE maintenance SET aircraft_id = '$aircraft_id', date = '$date', description = '$description' WHERE maintenance_id = '$maintenance_id'";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete maintenance record
if (isset($_GET['delete'])) {
    $maintenance_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM maintenance WHERE maintenance_id = $maintenance_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch maintenance records with filtering and sorting
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_aircraft_id'])) {
        $filter_aircraft_id = $conn->real_escape_string($_POST['filter_aircraft_id']);
        $filterClauses[] = "aircraft_id LIKE '%$filter_aircraft_id%'";
    }
    if (!empty($_POST['filter_description'])) {
        $filter_description = $conn->real_escape_string($_POST['filter_description']);
        $filterClauses[] = "description LIKE '%$filter_description%'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'maintenance_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM maintenance $whereSQL ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

// Edit form population
$current_record = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM maintenance WHERE maintenance_id = $edit_id");
    $current_record = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Management</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            background-image: url('maintenance.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        header, table, form {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
        }
    </style>
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
        <h1>Maintenance Management</h1>
    </header>

    <main>
    <div class="form-container">
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <form method="POST">
            <h2><?php echo $current_record ? "Edit Maintenance Record" : "Add Maintenance Record"; ?></h2>
            <input type="hidden" name="maintenance_id" value="<?php echo $current_record['maintenance_id'] ?? ''; ?>">
            <input type="text" name="aircraft_id" placeholder="Aircraft ID" value="<?php echo $current_record['aircraft_id'] ?? ''; ?>" required>
            <input type="date" name="date" value="<?php echo $current_record['date'] ?? ''; ?>" required>
            <textarea name="description" placeholder="Description" required><?php echo $current_record['description'] ?? ''; ?></textarea>
            <button type="submit" name="<?php echo $current_record ? 'update' : 'insert'; ?>">
                <?php echo $current_record ? "Update Record" : "Add Record"; ?>
            </button>
        </form>

        <!-- Filter Form -->
        <form method="POST">
            <h2>Filter Records</h2>
            <input type="text" name="filter_aircraft_id" placeholder="Aircraft ID">
            <input type="text" name="filter_description" placeholder="Description">
            <button type="submit" name="filter">Apply Filter</button>
        </form>
        </div>
        <!-- Maintenance Table -->
        <h2>Maintenance Records</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=maintenance_id&sort_order=<?php echo $next_order; ?>">Maintenance ID</a></th>
                    <th><a href="?sort_column=aircraft_id&sort_order=<?php echo $next_order; ?>">Aircraft ID</a></th>
                    <th><a href="?sort_column=date&sort_order=<?php echo $next_order; ?>">Date</a></th>
                    <th><a href="?sort_column=description&sort_order=<?php echo $next_order; ?>">Description</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['maintenance_id']}</td>
                                <td>{$row['aircraft_id']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['description']}</td>
                                <td>
                                    <a href='?edit={$row['maintenance_id']}'>Edit</a> | 
                                    <a href='?delete={$row['maintenance_id']}' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
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
