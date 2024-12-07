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

// Insert crew member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $role = $conn->real_escape_string($_POST['role']);
    $experience = $conn->real_escape_string($_POST['experience']);

    $result = $conn->query("SELECT MAX(crew_id) AS max_id FROM crew");
    $row = $result->fetch_assoc();
    $new_crew_id = $row['max_id'] + 1;

    $sql = "INSERT INTO crew (crew_id, name, role, experience) VALUES ('$new_crew_id', '$name', '$role', '$experience')";
    $conn->query($sql);
	}


// Delete crew member
if (isset($_GET['delete'])) {
    $crew_id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM crew WHERE crew_id = $crew_id";
    $conn->query($sql);
}

// Update crew member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $crew_id = $conn->real_escape_string($_POST['crew_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $role = $conn->real_escape_string($_POST['role']);
    $experience = $conn->real_escape_string($_POST['experience']);

    $sql = "UPDATE crew SET name = '$name', role = '$role', experience = '$experience' WHERE crew_id = $crew_id";
    $conn->query($sql);
}

// Filtering logic
$filterClauses = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
    if (!empty($_POST['filter_name'])) {
        $filter_name = $conn->real_escape_string($_POST['filter_name']);
        $filterClauses[] = "name LIKE '%$filter_name%'";
    }
    if (!empty($_POST['filter_role'])) {
        $filter_role = $conn->real_escape_string($_POST['filter_role']);
        $filterClauses[] = "role LIKE '%$filter_role%'";
    }
    if (!empty($_POST['filter_experience'])) {
        $filter_experience = $conn->real_escape_string($_POST['filter_experience']);
        $filterClauses[] = "experience = '$filter_experience'";
    }
}

// Sorting logic
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'crew_id';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'DESC' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$whereSQL = count($filterClauses) > 0 ? "WHERE " . implode(" AND ", $filterClauses) : "";
$sql = "SELECT * FROM crew $whereSQL ORDER BY $sort_column $sort_order";

$result = $conn->query($sql);

// Load crew for updating
$current_crew = null;
if (isset($_GET['edit'])) {
    $edit_id = $conn->real_escape_string($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM crew WHERE crew_id = $edit_id");
    $current_crew = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crew Management</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            background-image: url('crew.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: white;
        }
        .form-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-container form {
            flex: 1;
            padding: 15px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.7);
            color: white;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        table th a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<header style="cursor: pointer;" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">
    <h1>Crew Management</h1>
</header>


    <main>
    <button onclick="location.href='index.html'" style="margin-top: 5px; padding: 5px 5px; font-size: 10px;">Back</button>
        <div class="form-container">
            <form method="POST">
                <h2><?php echo $current_crew ? "Update Crew Member" : "Add New Crew Member"; ?></h2>
                <input type="hidden" name="crew_id" value="<?php echo $current_crew['crew_id'] ?? ''; ?>">
                <input type="text" name="name" placeholder="Name" value="<?php echo $current_crew['name'] ?? ''; ?>" required>
                <input type="text" name="role" placeholder="Role" value="<?php echo $current_crew['role'] ?? ''; ?>" required>
                <input type="number" name="experience" placeholder="Experience (Years)" value="<?php echo $current_crew['experience'] ?? ''; ?>" required>
                <button type="submit" name="<?php echo $current_crew ? 'update' : 'insert'; ?>">
                    <?php echo $current_crew ? "Update Crew" : "Add Crew"; ?>
                </button>
            </form>

            <!-- Filter Form -->
            <form method="POST">
                <h2>Filter Crew</h2>
                <input type="text" name="filter_name" placeholder="Name">
                <input type="text" name="filter_role" placeholder="Role">
                <input type="number" name="filter_experience" placeholder="Experience">
                <button type="submit" name="filter">Apply Filter</button>
            </form>
        </div>

        <h2>Crew List</h2>
        <table>
            <thead>
                <tr>
                    <th><a href="?sort_column=crew_id&sort_order=<?php echo $next_order; ?>">Crew ID</a></th>
                    <th><a href="?sort_column=name&sort_order=<?php echo $next_order; ?>">Name</a></th>
                    <th><a href="?sort_column=role&sort_order=<?php echo $next_order; ?>">Role</a></th>
                    <th><a href="?sort_column=experience&sort_order=<?php echo $next_order; ?>">Experience</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['crew_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['role']}</td>
                                <td>{$row['experience']}</td>
                                <td>
                                    <a href='?edit={$row['crew_id']}'>Edit</a> |
                                    <a href='?delete={$row['crew_id']}' onclick='return confirm(\"Are you sure you want to delete this crew member?\")'>Delete</a>
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
