<?php
// Start the session and include necessary files
session_start();
include 'includes/db_connect.php';

// Handle Delete
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM schedules WHERE flight_id = ?");
    $stmt->execute([$_POST['flight_id']]);
    $_SESSION['message'] = 'Flight schedule deleted successfully.';
    header("Location: flights.php");
    exit();
}

// Handle Add/Edit
if (isset($_POST['submit'])) {
    try {
        $pdo->beginTransaction();
        
        $departure_time = $_POST['departure_date'] . ' ' . $_POST['departure_time'];
        $arrival_time = $_POST['arrival_date'] . ' ' . $_POST['arrival_time'];
        
        if (isset($_POST['schedule_id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE schedules SET flight_id = ?, departure_time = ?, arrival_time = ? WHERE schedule_id = ?");
            $stmt->execute([$_POST['flight_id'], $departure_time, $arrival_time, $_POST['schedule_id']]);
            $_SESSION['message'] = 'Flight schedule updated successfully.';
        } else {
            // Insert
            $next_id = $pdo->query("SELECT MAX(schedule_id) + 1 FROM schedules")->fetchColumn();
            $next_id = $next_id ?: 1;
            
            $stmt = $pdo->prepare("INSERT INTO schedules (schedule_id, flight_id, departure_time, arrival_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([$next_id, $_POST['flight_id'], $departure_time, $arrival_time]);
            $_SESSION['message'] = 'Flight schedule added successfully.';
        }
        
        $pdo->commit();
        header("Location: flights.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: flights.php");
        exit();
    }
}

// Include header after all redirects
include 'includes/header.php';

// Display messages if any
if (isset($_SESSION['message'])) {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// Get schedule for editing if ID is provided
$editing = false;
$schedule = null;
if (isset($_GET['edit'])) {
    $editing = true;
    $stmt = $pdo->prepare("SELECT * FROM schedules WHERE schedule_id = ?");
    $stmt->execute([$_GET['edit']]);
    $schedule = $stmt->fetch();
}

// Fetch all airports for dropdowns
$airports = $pdo->query("SELECT * FROM airports ORDER BY code")->fetchAll();

// Fetch all aircraft for dropdowns
$aircraft = $pdo->query("SELECT * FROM aircraft ORDER BY model")->fetchAll();

// Fetch all available flights with route information
$flights = $pdo->query("
    SELECT DISTINCT s.flight_id, 
           a.model as aircraft_model,
           dep.code as departure_code, 
           arr.code as arrival_code
    FROM schedules s
    JOIN routes r ON s.flight_id = r.route_id
    JOIN airports dep ON r.departure_airport_id = dep.airport_id
    JOIN airports arr ON r.arrival_airport_id = arr.airport_id
    JOIN aircraft a ON a.aircraft_id = s.flight_id
    ORDER BY s.flight_id
")->fetchAll();

// Fetch all schedules with related information
$schedules = $pdo->query("
    SELECT s.schedule_id, 
           s.flight_id, 
           s.departure_time, 
           s.arrival_time,
           a.model as aircraft_model,
           dep.code as departure_code, 
           dep.city as departure_city,
           arr.code as arrival_code, 
           arr.city as arrival_city
    FROM schedules s
    JOIN routes r ON s.flight_id = r.route_id
    JOIN airports dep ON r.departure_airport_id = dep.airport_id
    JOIN airports arr ON r.arrival_airport_id = arr.airport_id
    JOIN aircraft a ON a.aircraft_id = s.flight_id
    ORDER BY s.departure_time
")->fetchAll();

// Add debug output to check what's being fetched
echo "<!-- Debug: Flights fetched: " . count($flights) . " -->\n";
foreach ($flights as $flight) {
    echo "<!-- Flight: " . print_r($flight, true) . " -->\n";
}
?>

<!-- Add/Edit Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit' : 'Add' ?> Flight Schedule</h2>
    <form method="POST" class="space-y-4">
        <?php if ($editing): ?>
            <input type="hidden" name="schedule_id" value="<?= $schedule['schedule_id'] ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Flight</label>
                <select name="flight_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <option value="">Select a Flight</option>
                    <?php foreach ($flights as $flight): ?>
                        <option value="<?= $flight['flight_id'] ?>" 
                                <?= ($editing && $schedule['flight_id'] == $flight['flight_id']) ? 'selected' : '' ?>>
                            Flight <?= $flight['flight_id'] ?>: 
                            <?= htmlspecialchars($flight['departure_code']) ?> → 
                            <?= htmlspecialchars($flight['arrival_code']) ?> 
                            (<?= htmlspecialchars($flight['aircraft_model']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Departure Date</label>
                <input type="date" name="departure_date" 
                       value="<?= $editing ? date('Y-m-d', strtotime($schedule['departure_time'])) : '' ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Departure Time</label>
                <input type="time" name="departure_time" 
                       value="<?= $editing ? date('H:i', strtotime($schedule['departure_time'])) : '' ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Arrival Date</label>
                <input type="date" name="arrival_date" 
                       value="<?= $editing ? date('Y-m-d', strtotime($schedule['arrival_time'])) : '' ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Arrival Time</label>
                <input type="time" name="arrival_time" 
                       value="<?= $editing ? date('H:i', strtotime($schedule['arrival_time'])) : '' ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            <button type="submit" name="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <?= $editing ? 'Update' : 'Add' ?> Flight Schedule
            </button>
            
            <?php if ($editing): ?>
                <a href="index.php" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Flight Schedules List -->
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Flight Schedules</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aircraft</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrival</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($schedules as $s): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= htmlspecialchars($s['departure_code']) ?> → <?= htmlspecialchars($s['arrival_code']) ?>
                            <div class="text-sm text-gray-500">
                                <?= htmlspecialchars($s['departure_city']) ?> to <?= htmlspecialchars($s['arrival_city']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['aircraft_model']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= date('Y-m-d H:i', strtotime($s['departure_time'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= date('Y-m-d H:i', strtotime($s['arrival_time'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="?edit=<?= $s['schedule_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="flight_id" value="<?= $s['flight_id'] ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this flight schedule?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 