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

// Insert passenger record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $result = $conn->query("SELECT MAX(passenger_id) AS max_id FROM passengers");
    $row = $result->fetch_assoc();
    $new_passenger_id = $row['max_id'] + 1;

    $sql = "INSERT INTO passengers (passenger_id, name, email, phone_number) VALUES ('$new_passenger_id','$name', '$email', '$phone_number')";
    $conn->query($sql);
	
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update passenger record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $passenger_id = $conn->real_escape_string($_POST['passenger_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    
    $sql = "UPDATE passengers SET name = '$name', email = '$email', phone_number = '$phone_number' WHERE passenger_id = '$passenger_id'";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete passenger record
if (isset($_GET['delete'])) {
    $passenger_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM passengers WHERE passenger_id = $passenger_id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch passengers with filtering and sorting
$filterClauses = [];
if (isset($_POST['filter'])) {
    if (!empty($_POST['filter_name'])) {
        $filter_name = $conn->real_escape_string($_POST['filter_name']);
        $filterClauses[] = "name LIKE '%$filter_name%'";
    }
    if (!empty($_POST['filter_email'])) {
        $filter_email = $conn->real_escape_string($_POST['filter_email']);
        $filterClauses[] = "email LIKE '%$filter_email%'";
    }
}

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'passenger_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM passengers $whereSQL ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

// Edit form population
$current_record = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM passengers WHERE passenger_id = $edit_id");
    $current_record = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Management</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            background-image: url('passengers.jpeg');
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
        <h1>Passenger Management</h1>
    </header>

    <main>
    <div class="form-container">
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>

        <!-- Insert/Update Form -->
        <form method="POST">
            <h2><?php echo $current_record ? "Edit Passenger Record" : "Add Passenger Record"; ?></h2>
            <input type="hidden" name="passenger_id" value="<?php echo $current_record['passenger_id'] ?? ''; ?>">
            <input type="text" name="name" placeholder="Name" value="<?php echo $current_record['name'] ?? ''; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $current_record['email'] ?? ''; ?>" required>
            <input type="text" name="phone_number" placeholder="Phone Number" value="<?php echo $current_record['phone_number'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo $current_record ? 'update' : 'insert'; ?>">
                <?php echo $current_record ? "Update Record" : "Add Record"; ?>
            </button>
        </form>

        <!-- Filter Form -->
        <form method="POST">
            <h2>Filter Records</h2>
            <input type="text" name="filter_name" placeholder="Name">
            <input type="email" name="filter_email" placeholder="Email">
            <button type="submit" name="filter">Apply Filter</button>
        </form>
        </div>

        <!-- Passenger Table -->
        <h2>Passenger Records</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=passenger_id&sort_order=<?php echo $next_order; ?>">Passenger ID</a></th>
                    <th><a href="?sort_column=name&sort_order=<?php echo $next_order; ?>">Name</a></th>
                    <th><a href="?sort_column=email&sort_order=<?php echo $next_order; ?>">Email</a></th>
                    <th><a href="?sort_column=phone_number&sort_order=<?php echo $next_order; ?>">Phone Number</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['passenger_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone_number']}</td>
                                <td>
                                    <a href='?edit={$row['passenger_id']}'>Edit</a> | 
                                    <a href='?delete={$row['passenger_id']}' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
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
