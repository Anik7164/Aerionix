<?php
session_start();
include 'includes/db_connect.php';

// Handle Delete
if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM routes WHERE route_id = ?");
        $stmt->execute([$_POST['route_id']]);
        $_SESSION['message'] = 'Route deleted successfully.';
        header("Location: routes.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: routes.php");
        exit();
    }
}

// Handle Add/Edit
if (isset($_POST['submit'])) {
    try {
        $pdo->beginTransaction();
        
        if (isset($_POST['route_id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE routes SET departure_airport_id = ?, arrival_airport_id = ?, distance = ? WHERE route_id = ?");
            $stmt->execute([$_POST['departure_airport_id'], $_POST['arrival_airport_id'], $_POST['distance'], $_POST['route_id']]);
            $_SESSION['message'] = 'Route updated successfully.';
        } else {
            // Insert
            $next_id = $pdo->query("SELECT MAX(route_id) + 1 FROM routes")->fetchColumn();
            $next_id = $next_id ?: 1;
            
            $stmt = $pdo->prepare("INSERT INTO routes (route_id, departure_airport_id, arrival_airport_id, distance) VALUES (?, ?, ?, ?)");
            $stmt->execute([$next_id, $_POST['departure_airport_id'], $_POST['arrival_airport_id'], $_POST['distance']]);
            $_SESSION['message'] = 'Route added successfully.';
        }
        
        $pdo->commit();
        header("Location: routes.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: routes.php");
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

// Get route for editing if ID is provided
$editing = false;
$route = null;
if (isset($_GET['edit'])) {
    $editing = true;
    $stmt = $pdo->prepare("SELECT * FROM routes WHERE route_id = ?");
    $stmt->execute([$_GET['edit']]);
    $route = $stmt->fetch();
}

// Fetch all airports for dropdowns
$airports = $pdo->query("SELECT * FROM airports ORDER BY code")->fetchAll();

// Fetch all routes with airport information
$stmt = $pdo->query("
    SELECT r.*, 
           dep.code as departure_code, dep.city as departure_city,
           arr.code as arrival_code, arr.city as arrival_city
    FROM routes r
    JOIN airports dep ON r.departure_airport_id = dep.airport_id
    JOIN airports arr ON r.arrival_airport_id = arr.airport_id
    ORDER BY dep.code, arr.code
");
$routes = $stmt->fetchAll();
?>

<!-- Add/Edit Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit' : 'Add' ?> Route</h2>
    <form method="POST" class="space-y-4">
        <?php if ($editing): ?>
            <input type="hidden" name="route_id" value="<?= $route['route_id'] ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Departure Airport</label>
                <select name="departure_airport_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <option value="">Select Departure Airport</option>
                    <?php foreach ($airports as $airport): ?>
                        <option value="<?= $airport['airport_id'] ?>" 
                                <?= $editing && $route['departure_airport_id'] == $airport['airport_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($airport['code']) ?> - <?= htmlspecialchars($airport['city']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Arrival Airport</label>
                <select name="arrival_airport_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <option value="">Select Arrival Airport</option>
                    <?php foreach ($airports as $airport): ?>
                        <option value="<?= $airport['airport_id'] ?>"
                                <?= $editing && $route['arrival_airport_id'] == $airport['airport_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($airport['code']) ?> - <?= htmlspecialchars($airport['city']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Distance (km)</label>
                <input type="number" name="distance" 
                       value="<?= $editing ? $route['distance'] : '' ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            <button type="submit" name="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <?= $editing ? 'Update' : 'Add' ?> Route
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

<!-- Routes List -->
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Routes List</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($routes as $r): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium">
                                <?= htmlspecialchars($r['departure_code']) ?> → <?= htmlspecialchars($r['arrival_code']) ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                <?= htmlspecialchars($r['departure_city']) ?> to <?= htmlspecialchars($r['arrival_city']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= number_format($r['distance']) ?> km
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="?edit=<?= $r['route_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="route_id" value="<?= $r['route_id'] ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this route?')">
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