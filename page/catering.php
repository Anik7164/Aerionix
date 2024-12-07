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

// Insert catering
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $menu = $conn->real_escape_string($_POST['menu']);

    $result = $conn->query("SELECT MAX(catering_id) AS max_id FROM catering");
    $row = $result->fetch_assoc();
    $new_catering_id = $row['max_id'] + 1;

    $sql = "INSERT INTO catering (catering_id, flight_id, date, menu) VALUES ('$new_catering_id', '$flight_id', '$date', '$menu')";
    $conn->query($sql);

    // Redirect back to the same page to reload
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); // Ensure the script stops after redirect
}

// Delete catering
if (isset($_GET['delete'])) {
    $catering_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM catering WHERE catering_id = $catering_id";
    $conn->query($sql);

    // Redirect back to the same page to reload
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); // Ensure the script stops after redirect
}

// Update catering
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $catering_id = $conn->real_escape_string($_POST['catering_id']);
    $flight_id = $conn->real_escape_string($_POST['flight_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $menu = $conn->real_escape_string($_POST['menu']);

    $sql = "UPDATE catering SET flight_id = '$flight_id', date = '$date', menu = '$menu' WHERE catering_id = $catering_id";
    $conn->query($sql);

    // Redirect back to the same page to reload
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); // Ensure the script stops after redirect
}


// Filtering logic
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_flight_id'])) {
        $filter_flight_id = $conn->real_escape_string($_POST['filter_flight_id']);
        $filterClauses[] = "flight_id LIKE '%$filter_flight_id%'";
    }
    if (!empty($_POST['filter_menu'])) {
        $filter_menu = $conn->real_escape_string($_POST['filter_menu']);
        $filterClauses[] = "menu LIKE '%$filter_menu%'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'catering_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM catering $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);

$current_catering = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM catering WHERE catering_id = $edit_id");
    $current_catering = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catering Management</title>
    <link rel="stylesheet" href="ss.css">
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
        <h1>Catering Management</h1>
    </header>

    <main>
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <div class="form-container">
            <form method="POST">
                <h2><?php echo $current_catering ? "Update Catering" : "Add New Catering"; ?></h2>
                <input type="hidden" name="catering_id" value="<?php echo $current_catering['catering_id'] ?? ''; ?>">
                <input type="text" name="flight_id" placeholder="Flight ID" value="<?php echo $current_catering['flight_id'] ?? ''; ?>" required>
                <input type="date" name="date" placeholder="Date" value="<?php echo $current_catering['date'] ?? ''; ?>" required>
                <input type="text" name="menu" placeholder="Menu" value="<?php echo $current_catering['menu'] ?? ''; ?>" required>
                <button type="submit" name="<?php echo $current_catering ? 'update' : 'insert'; ?>">
                    <?php echo $current_catering ? "Update Catering" : "Add Catering"; ?>
                </button>
            </form>

            <form method="POST">
                <h2>Filter Catering</h2>
                <input type="text" name="filter_flight_id" placeholder="Flight ID">
                <input type="text" name="filter_menu" placeholder="Menu">
                <button type="submit" name="filter">Apply Filter</button>
            </form>
        </div>

        <h2>Catering List</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=catering_id&sort_order=<?php echo $next_order; ?>">Catering ID</a></th>
                    <th><a href="?sort_column=flight_id&sort_order=<?php echo $next_order; ?>">Flight ID</a></th>
                    <th><a href="?sort_column=date&sort_order=<?php echo $next_order; ?>">Date</a></th>
                    <th><a href="?sort_column=menu&sort_order=<?php echo $next_order; ?>">Menu</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['catering_id']}</td>
                                <td>{$row['flight_id']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['menu']}</td>
                                <td>
                                    <a href='?edit={$row['catering_id']}'>Edit</a> |
                                    <a href='?delete={$row['catering_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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
