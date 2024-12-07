<?php
// fuel.php

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

// Insert fuel record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $aircraft_id = $conn->real_escape_string($_POST['aircraft_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $result = $conn->query("SELECT MAX(fuel_id) AS max_id FROM fuel");
    $row = $result->fetch_assoc();
    $new_fuel_id = $row['max_id'] + 1;
 $sql = "INSERT INTO fuel (fuel_id, aircraft_id, date, quantity) VALUES ('$new_fuel_id', '$aircraft_id', '$date', '$quantity')";
    $conn->query($sql);
    header("Location: fuel.php");
    exit();
}

// Delete fuel record
if (isset($_GET['delete'])) {
    $fuel_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM fuel WHERE fuel_id = $fuel_id";
    $conn->query($sql);
    header("Location: fuel.php");
    exit();
}

// Update fuel record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $fuel_id = $conn->real_escape_string($_POST['fuel_id']);
    $aircraft_id = $conn->real_escape_string($_POST['aircraft_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $sql = "UPDATE fuel SET aircraft_id = '$aircraft_id', date = '$date', quantity = '$quantity' WHERE fuel_id = $fuel_id";
    $conn->query($sql);
    header("Location: fuel.php");
    exit();
}

// Filtering and sorting
$filter = "";
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'fuel_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
    $filter_aircraft_id = $conn->real_escape_string($_POST['filter_aircraft_id']);
    $filter_date = $conn->real_escape_string($_POST['filter_date']);
    if (!empty($filter_aircraft_id)) {
        $filter .= "aircraft_id LIKE '%$filter_aircraft_id%' AND ";
    }
    if (!empty($filter_date)) {
        $filter .= "date LIKE '%$filter_date%' AND ";
    }
    $filter = rtrim($filter, "AND ");
}

$whereSQL = !empty($filter) ? "WHERE $filter" : "";
$sql = "SELECT * FROM fuel $whereSQL ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

// Editing a fuel record
$current_fuel = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM fuel WHERE fuel_id = $edit_id");
    $current_fuel = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Data Management</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            background-image: url('fuel.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
        <h1>Fuel Data Management</h1>
    </header>

    <main>
    <div class="form-container">
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <form method="POST">
            <h2><?php echo $current_fuel ? "Update Fuel Record" : "Add New Fuel Record"; ?></h2>
            <input type="hidden" name="fuel_id" value="<?php echo $current_fuel['fuel_id'] ?? ''; ?>">
            <input type="text" name="aircraft_id" placeholder="Aircraft ID" value="<?php echo $current_fuel['aircraft_id'] ?? ''; ?>" required>
            <input type="date" name="date" placeholder="Date" value="<?php echo $current_fuel['date'] ?? ''; ?>" required>
            <input type="number" name="quantity" placeholder="Quantity (liters)" value="<?php echo $current_fuel['quantity'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo $current_fuel ? 'update' : 'insert'; ?>">
                <?php echo $current_fuel ? "Update Fuel" : "Add Fuel"; ?>
            </button>
        </form>

        <form method="POST">
            <h2>Filter Records</h2>
            <input type="text" name="filter_aircraft_id" placeholder="Aircraft ID">
            <input type="date" name="filter_date" placeholder="Date">
            <button type="submit" name="filter">Apply Filter</button>
        </form>
        </div>
        <h2>Fuel Records</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=fuel_id&sort_order=<?php echo $next_order; ?>">Fuel ID</a></th>
                    <th><a href="?sort_column=aircraft_id&sort_order=<?php echo $next_order; ?>">Aircraft ID</a></th>
                    <th><a href="?sort_column=date&sort_order=<?php echo $next_order; ?>">Date</a></th>
                    <th><a href="?sort_column=quantity&sort_order=<?php echo $next_order; ?>">Quantity (liters)</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['fuel_id']}</td>
                                <td>{$row['aircraft_id']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['quantity']}</td>
                                <td>
                                    <a href='?edit={$row['fuel_id']}'>Edit</a> |
                                    <a href='?delete={$row['fuel_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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
