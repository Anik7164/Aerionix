<?php
session_start();
include 'includes/db_connect.php';

// Handle Delete
if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM airports WHERE airport_id = ?");
        $stmt->execute([$_POST['airport_id']]);
        $_SESSION['message'] = 'Airport deleted successfully.';
        header("Location: airports.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: airports.php");
        exit();
    }
}

// Handle Add/Edit
if (isset($_POST['submit'])) {
    try {
        $pdo->beginTransaction();
        
        if (isset($_POST['airport_id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE airports SET code = ?, name = ?, city = ?, country = ? WHERE airport_id = ?");
            $stmt->execute([$_POST['code'], $_POST['name'], $_POST['city'], $_POST['country'], $_POST['airport_id']]);
            $_SESSION['message'] = 'Airport updated successfully.';
        } else {
            // Insert
            $next_id = $pdo->query("SELECT MAX(airport_id) + 1 FROM airports")->fetchColumn();
            $next_id = $next_id ?: 1;
            
            $stmt = $pdo->prepare("INSERT INTO airports (airport_id, code, name, city, country) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$next_id, $_POST['code'], $_POST['name'], $_POST['city'], $_POST['country']]);
            $_SESSION['message'] = 'Airport added successfully.';
        }
        
        $pdo->commit();
        header("Location: airports.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: airports.php");
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

// Get airport for editing if ID is provided
$editing = false;
$airport = null;
if (isset($_GET['edit'])) {
    $editing = true;
    $stmt = $pdo->prepare("SELECT * FROM airports WHERE airport_id = ?");
    $stmt->execute([$_GET['edit']]);
    $airport = $stmt->fetch();
}

// Fetch all airports
$stmt = $pdo->query("SELECT * FROM airports ORDER BY code");
$airports_list = $stmt->fetchAll();
?>

<!-- Add/Edit Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit' : 'Add' ?> Airport</h2>
    <form method="POST" class="space-y-4">
        <?php if ($editing): ?>
            <input type="hidden" name="airport_id" value="<?= $airport['airport_id'] ?>">
        <?php endif; ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Airport Code</label>
            <input type="text" name="code" maxlength="3" value="<?= $editing ? $airport['code'] : '' ?>" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Airport Name</label>
            <input type="text" name="name" value="<?= $editing ? $airport['name'] : '' ?>"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">City</label>
            <input type="text" name="city" value="<?= $editing ? $airport['city'] : '' ?>"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Country</label>
            <input type="text" name="country" value="<?= $editing ? $airport['country'] : '' ?>"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
        </div>
        
        <div class="flex items-center space-x-2">
            <button type="submit" name="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <?= $editing ? 'Update' : 'Add' ?> Airport
            </button>
            
            <?php if ($editing): ?>
                <a href="airports.php" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Airports List -->
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Airports List</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($airports_list as $a): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium"><?= htmlspecialchars($a['code']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($a['name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($a['city']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($a['country']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="?edit=<?= $a['airport_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="airport_id" value="<?= $a['airport_id'] ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this airport?')">
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