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

// Insert airport
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $code = $conn->real_escape_string($_POST['code']);
    $name = $conn->real_escape_string($_POST['name']);
    $city = $conn->real_escape_string($_POST['city']);
    $country = $conn->real_escape_string($_POST['country']);
    
    $result = $conn->query("SELECT MAX(airport_id) AS max_id FROM airports");
    $row = $result->fetch_assoc();
    $new_airport_id = $row['max_id'] + 1;

    $sql = "INSERT INTO airports (airport_id, code, name, city, country) VALUES ('$new_airport_id', '$code', '$name', '$city', '$country')";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete airport
if (isset($_GET['delete'])) {
    $airport_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM airports WHERE airport_id = $airport_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update airport
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $airport_id = $conn->real_escape_string($_POST['airport_id']);
    $code = $conn->real_escape_string($_POST['code']);
    $name = $conn->real_escape_string($_POST['name']);
    $city = $conn->real_escape_string($_POST['city']);
    $country = $conn->real_escape_string($_POST['country']);

    $sql = "UPDATE airports SET code = '$code', name = '$name', city = '$city', country = '$country' WHERE airport_id = $airport_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Filtering logic and sorting logic remain the same...
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_name'])) {
        $filter_name = $conn->real_escape_string($_POST['filter_name']);
        $filterClauses[] = "name LIKE '%$filter_name%'";
    }
    if (!empty($_POST['filter_city'])) {
        $filter_city = $conn->real_escape_string($_POST['filter_city']);
        $filterClauses[] = "city LIKE '%$filter_city%'";
    }
    if (!empty($_POST['filter_country'])) {
        $filter_country = $conn->real_escape_string($_POST['filter_country']);
        $filterClauses[] = "country LIKE '%$filter_country%'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'airport_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM airports $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);

$current_airport = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM airports WHERE airport_id = $edit_id");
    $current_airport = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airport Management</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        /* Add your styles */
    </style>
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
        <h1>Airport Management</h1>
    </header>

    <main>
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>

        <div class="form-container">
            <!-- Insert or Update Form -->
            <form method="POST">
                <h2><?php echo $current_airport ? "Update Airport" : "Add New Airport"; ?></h2>
                <input type="hidden" name="airport_id" value="<?php echo $current_airport['airport_id'] ?? ''; ?>">
                <input type="text" name="code" placeholder="Airport Code" value="<?php echo $current_airport['code'] ?? ''; ?>" required>
                <input type="text" name="name" placeholder="Airport Name" value="<?php echo $current_airport['name'] ?? ''; ?>" required>
                <input type="text" name="city" placeholder="City" value="<?php echo $current_airport['city'] ?? ''; ?>" required>
                <input type="text" name="country" placeholder="Country" value="<?php echo $current_airport['country'] ?? ''; ?>" required>
                <button type="submit" name="<?php echo $current_airport ? 'update' : 'insert'; ?>">
                    <?php echo $current_airport ? "Update Airport" : "Add Airport"; ?>
                </button>
            </form>

            <!-- Filter Form -->
            <form method="POST">
                <h2>Filter Airports</h2>
                <input type="text" name="filter_name" placeholder="Name">
                <input type="text" name="filter_city" placeholder="City">
                <input type="text" name="filter_country" placeholder="Country">
                <button type="submit" name="filter">Apply Filter</button>
            </form>
        </div>

        <h2>Airport List</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=airport_id&sort_order=<?php echo $next_order; ?>">Airport ID</a></th>
                    <th><a href="?sort_column=code&sort_order=<?php echo $next_order; ?>">Code</a></th>
                    <th><a href="?sort_column=name&sort_order=<?php echo $next_order; ?>">Name</a></th>
                    <th><a href="?sort_column=city&sort_order=<?php echo $next_order; ?>">City</a></th>
                    <th><a href="?sort_column=country&sort_order=<?php echo $next_order; ?>">Country</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['airport_id']}</td>
                                <td>{$row['code']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['city']}</td>
                                <td>{$row['country']}</td>
                                <td>
                                    <a href='?edit={$row['airport_id']}'>Edit</a> |
                                    <a href='?delete={$row['airport_id']}' onclick='return confirm(\"Are you sure you want to delete this airport?\")'>Delete</a>
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
