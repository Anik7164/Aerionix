<?php
session_start();
include 'includes/db_connect.php';

// Handle Delete
if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE booking_id = ?");
        $stmt->execute([$_POST['booking_id']]);
        $_SESSION['message'] = 'Booking deleted successfully.';
        header("Location: bookings.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: bookings.php");
        exit();
    }
}

// Handle Add/Edit
if (isset($_POST['submit'])) {
    try {
        $pdo->beginTransaction();
        
        if (isset($_POST['booking_id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE bookings SET flight_id = ?, passenger_id = ?, seat_number = ? WHERE booking_id = ?");
            $stmt->execute([$_POST['flight_id'], $_POST['passenger_id'], $_POST['seat_number'], $_POST['booking_id']]);
            $_SESSION['message'] = 'Booking updated successfully.';
        } else {
            // Insert
            $next_id = $pdo->query("SELECT MAX(booking_id) + 1 FROM bookings")->fetchColumn();
            $next_id = $next_id ?: 1;
            
            $stmt = $pdo->prepare("INSERT INTO bookings (booking_id, flight_id, passenger_id, seat_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$next_id, $_POST['flight_id'], $_POST['passenger_id'], $_POST['seat_number']]);
            $_SESSION['message'] = 'Booking added successfully.';
        }
        
        $pdo->commit();
        header("Location: bookings.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: bookings.php");
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

// Get booking for editing if ID is provided
$editing = false;
$booking = null;
if (isset($_GET['edit'])) {
    $editing = true;
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ?");
    $stmt->execute([$_GET['edit']]);
    $booking = $stmt->fetch();
}

// Fetch all flights for dropdown
$flights = $pdo->query("
    SELECT s.flight_id, 
           dep.code as departure_code, 
           arr.code as arrival_code,
           s.departure_time
    FROM schedules s
    JOIN routes r ON s.flight_id = r.route_id
    JOIN airports dep ON r.departure_airport_id = dep.airport_id
    JOIN airports arr ON r.arrival_airport_id = arr.airport_id
    ORDER BY s.departure_time
")->fetchAll();

// Fetch all passengers for dropdown
$passengers = $pdo->query("SELECT * FROM passengers ORDER BY name")->fetchAll();

// Fetch all bookings with related information
$stmt = $pdo->query("
    SELECT b.*, 
           p.name as passenger_name,
           dep.code as departure_code,
           arr.code as arrival_code,
           s.departure_time
    FROM bookings b
    JOIN passengers p ON b.passenger_id = p.passenger_id
    JOIN schedules s ON b.flight_id = s.flight_id
    JOIN routes r ON s.flight_id = r.route_id
    JOIN airports dep ON r.departure_airport_id = dep.airport_id
    JOIN airports arr ON r.arrival_airport_id = arr.airport_id
    ORDER BY s.departure_time
");
$bookings = $stmt->fetchAll();
?>

<!-- Add/Edit Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit' : 'Add' ?> Booking</h2>
    <form method="POST" class="space-y-4">
        <?php if ($editing): ?>
            <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Flight</label>
                <select name="flight_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Flight</option>
                    <?php foreach ($flights as $flight): ?>
                        <option value="<?= $flight['flight_id'] ?>"
                                <?= $editing && $booking['flight_id'] == $flight['flight_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($flight['departure_code']) ?> → 
                            <?= htmlspecialchars($flight['arrival_code']) ?> 
                            (<?= date('Y-m-d H:i', strtotime($flight['departure_time'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Passenger</label>
                <select name="passenger_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Passenger</option>
                    <?php foreach ($passengers as $passenger): ?>
                        <option value="<?= $passenger['passenger_id'] ?>"
                                <?= $editing && $booking['passenger_id'] == $passenger['passenger_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($passenger['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Seat Number</label>
                <input type="text" name="seat_number" 
                       value="<?= $editing ? $booking['seat_number'] : '' ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            <button type="submit" name="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <?= $editing ? 'Update' : 'Add' ?> Booking
            </button>
            
            <?php if ($editing): ?>
                <a href="bookings.php" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Bookings List -->
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Bookings List</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flight</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passenger</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium">
                                <?= htmlspecialchars($b['departure_code']) ?> → <?= htmlspecialchars($b['arrival_code']) ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                <?= date('Y-m-d H:i', strtotime($b['departure_time'])) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($b['passenger_name']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($b['seat_number']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="?edit=<?= $b['booking_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this booking?')">
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