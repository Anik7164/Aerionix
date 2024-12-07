<?php
include 'includes/db_connect.php';
include 'includes/header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch statistics
echo "<!-- Debug: Fetching statistics -->\n";
$stats = [
    'flights' => $pdo->query("SELECT COUNT(*) FROM schedules")->fetchColumn(),
    'aircraft' => $pdo->query("SELECT COUNT(*) FROM aircraft")->fetchColumn(), 
    'passengers' => $pdo->query("SELECT COUNT(*) FROM passengers")->fetchColumn(),
    'bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
];
echo "<!-- Debug: Statistics fetched: " . print_r($stats, true) . " -->\n";

// Fetch upcoming flights
echo "<!-- Debug: Fetching upcoming flights -->\n";
$upcoming_flights = $pdo->query("
    SELECT s.*, 
           dep.code as departure_code, 
           arr.code as arrival_code,
           a.model as aircraft_model
    FROM schedules s
    JOIN routes r ON s.flight_id = r.route_id
    JOIN airports dep ON r.departure_airport_id = dep.airport_id
    JOIN airports arr ON r.arrival_airport_id = arr.airport_id
    JOIN aircraft a ON s.flight_id = a.aircraft_id
    WHERE s.departure_time >= NOW()
    ORDER BY s.departure_time
    LIMIT 5
")->fetchAll();
echo "<!-- Debug: Upcoming flights fetched: " . count($upcoming_flights) . " results -->\n";

// Fetch maintenance alerts
echo "<!-- Debug: Fetching maintenance alerts -->\n";
$maintenance_alerts = $pdo->query("
    SELECT m.*, a.model as aircraft_model,
           CASE 
               WHEN m.date = CURRENT_DATE THEN 'In Progress'
               WHEN m.date > CURRENT_DATE THEN 'Scheduled'
               ELSE 'Completed'
           END as status,
           m.description as maintenance_type
    FROM maintenance m
    JOIN aircraft a ON m.aircraft_id = a.aircraft_id
    WHERE m.date >= CURRENT_DATE
    ORDER BY m.date
    LIMIT 3
")->fetchAll();
echo "<!-- Debug: Maintenance alerts fetched: " . count($maintenance_alerts) . " results -->\n";
?>

<!-- Hero Section -->
<div class="bg-blue-600 text-white rounded-lg shadow-lg p-6 mb-6">
    <div class="max-w-4xl">
        <h1 class="text-3xl font-bold mb-2">Aerionix International</h1>
        <p class="text-blue-100 mb-4">Comprehensive flight operations and management platform</p>
        <div class="flex space-x-4">
            <a href="flights.php" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                Manage Flights
            </a>
            <a href="bookings.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-400 transition">
                New Booking
            </a>
        </div>
    </div>
</div>

<!-- Statistics Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Flights Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Flights</p>
                <p class="text-2xl font-semibold text-gray-700"><?= number_format($stats['flights']) ?></p>
            </div>
        </div>
    </div>

    <!-- Aircraft Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Aircraft</p>
                <p class="text-2xl font-semibold text-gray-700"><?= number_format($stats['aircraft']) ?></p>
            </div>
        </div>
    </div>

    <!-- Passengers Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Passengers</p>
                <p class="text-2xl font-semibold text-gray-700"><?= number_format($stats['passengers']) ?></p>
            </div>
        </div>
    </div>

    <!-- Bookings Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Bookings</p>
                <p class="text-2xl font-semibold text-gray-700"><?= number_format($stats['bookings']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions and Information -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Upcoming Flights -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Upcoming Flights</h2>
            <div class="space-y-4">
                <?php foreach ($upcoming_flights as $flight): ?>
                    <?php echo "<!-- Debug: Processing flight: " . print_r($flight, true) . " -->\n"; ?>
                    <div class="flex items-center justify-between border-b pb-4">
                        <div>
                            <div class="font-semibold text-lg">
                                <?= htmlspecialchars($flight['departure_code']) ?> → 
                                <?= htmlspecialchars($flight['arrival_code']) ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                <?= htmlspecialchars($flight['aircraft_model']) ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-medium">
                                <?= date('M d, H:i', strtotime($flight['departure_time'])) ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                Departure
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4">
                <a href="flights.php" class="text-blue-600 hover:text-blue-800 font-medium">View all flights →</a>
            </div>
        </div>
    </div>

    <!-- Maintenance Alerts -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Maintenance Alerts</h2>
            <div class="space-y-4">
                <?php foreach ($maintenance_alerts as $alert): ?>
                    <?php echo "<!-- Debug: Processing alert: " . print_r($alert, true) . " -->\n"; ?>
                    <div class="flex items-center justify-between border-b pb-4">
                        <div>
                            <div class="font-semibold">
                                <?= htmlspecialchars($alert['aircraft_model']) ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                <?= htmlspecialchars($alert['maintenance_type']) ?>
                            </div>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                <?php
                                switch($alert['status']) {
                                    case 'In Progress':
                                        echo 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'Scheduled':
                                        echo 'bg-blue-100 text-blue-800';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= htmlspecialchars($alert['status']) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4">
                <a href="maintenance.php" class="text-blue-600 hover:text-blue-800 font-medium">View maintenance records →</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>