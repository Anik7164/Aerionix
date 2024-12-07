<?php
session_start();
include 'includes/db_connect.php';

// Handle Delete
if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM aircraft WHERE aircraft_id = ?");
        $stmt->execute([$_POST['aircraft_id']]);
        $_SESSION['message'] = 'Aircraft deleted successfully.';
        header("Location: aircraft.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: aircraft.php");
        exit();
    }
}

// Handle Add/Edit
if (isset($_POST['submit'])) {
    try {
        $pdo->beginTransaction();
        
        if (isset($_POST['aircraft_id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE aircraft SET model = ?, capacity = ?, flight_range = ? WHERE aircraft_id = ?");
            $stmt->execute([$_POST['model'], $_POST['capacity'], $_POST['flight_range'], $_POST['aircraft_id']]);
            $_SESSION['message'] = 'Aircraft updated successfully.';
        } else {
            // Insert
            $next_id = $pdo->query("SELECT MAX(aircraft_id) + 1 FROM aircraft")->fetchColumn();
            $next_id = $next_id ?: 1;
            
            $stmt = $pdo->prepare("INSERT INTO aircraft (aircraft_id, model, capacity, flight_range) VALUES (?, ?, ?, ?)");
            $stmt->execute([$next_id, $_POST['model'], $_POST['capacity'], $_POST['flight_range']]);
            $_SESSION['message'] = 'Aircraft added successfully.';
        }
        
        $pdo->commit();
        header("Location: aircraft.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: aircraft.php");
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

// Get aircraft for editing if ID is provided
$editing = false;
$aircraft = null;
if (isset($_GET['edit'])) {
    $editing = true;
    $stmt = $pdo->prepare("SELECT * FROM aircraft WHERE aircraft_id = ?");
    $stmt->execute([$_GET['edit']]);
    $aircraft = $stmt->fetch();
}

// Fetch all aircraft
$stmt = $pdo->query("SELECT * FROM aircraft ORDER BY aircraft_id");
$aircraft_list = $stmt->fetchAll();
?>

<!-- Add/Edit Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit' : 'Add' ?> Aircraft</h2>
    <form method="POST" class="space-y-4">
        <?php if ($editing): ?>
            <input type="hidden" name="aircraft_id" value="<?= $aircraft['aircraft_id'] ?>">
        <?php endif; ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Model</label>
            <input type="text" name="model" value="<?= $editing ? $aircraft['model'] : '' ?>" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Capacity</label>
            <input type="number" name="capacity" value="<?= $editing ? $aircraft['capacity'] : '' ?>"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Flight Range (km)</label>
            <input type="number" name="flight_range" value="<?= $editing ? $aircraft['flight_range'] : '' ?>"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <button type="submit" name="submit" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <?= $editing ? 'Update' : 'Add' ?> Aircraft
        </button>
        
        <?php if ($editing): ?>
            <a href="aircraft.php" 
               class="ml-2 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Aircraft List -->
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Aircraft List</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Range (km)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($aircraft_list as $a): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $a['aircraft_id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($a['model']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $a['capacity'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $a['flight_range'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="?edit=<?= $a['aircraft_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="aircraft_id" value="<?= $a['aircraft_id'] ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this aircraft?')">
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