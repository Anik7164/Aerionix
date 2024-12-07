<?php
session_start();
include 'includes/db_connect.php';

// Handle Delete
if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM passengers WHERE passenger_id = ?");
        $stmt->execute([$_POST['passenger_id']]);
        $_SESSION['message'] = 'Passenger deleted successfully.';
        header("Location: passengers.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: passengers.php");
        exit();
    }
}

// Handle Add/Edit
if (isset($_POST['submit'])) {
    try {
        $pdo->beginTransaction();
        
        if (isset($_POST['passenger_id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE passengers SET name = ?, email = ?, phone_number = ? WHERE passenger_id = ?");
            $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone_number'], $_POST['passenger_id']]);
            $_SESSION['message'] = 'Passenger updated successfully.';
        } else {
            // Insert
            $next_id = $pdo->query("SELECT MAX(passenger_id) + 1 FROM passengers")->fetchColumn();
            $next_id = $next_id ?: 1;
            
            $stmt = $pdo->prepare("INSERT INTO passengers (passenger_id, name, email, phone_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$next_id, $_POST['name'], $_POST['email'], $_POST['phone_number']]);
            $_SESSION['message'] = 'Passenger added successfully.';
        }
        
        $pdo->commit();
        header("Location: passengers.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: passengers.php");
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

// Get passenger for editing if ID is provided
$editing = false;
$passenger = null;
if (isset($_GET['edit'])) {
    $editing = true;
    $stmt = $pdo->prepare("SELECT * FROM passengers WHERE passenger_id = ?");
    $stmt->execute([$_GET['edit']]);
    $passenger = $stmt->fetch();
}

// Fetch all passengers
$stmt = $pdo->query("SELECT * FROM passengers ORDER BY name");
$passengers_list = $stmt->fetchAll();
?>

<!-- Add/Edit Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit' : 'Add' ?> Passenger</h2>
    <form method="POST" class="space-y-4">
        <?php if ($editing): ?>
            <input type="hidden" name="passenger_id" value="<?= $passenger['passenger_id'] ?>">
        <?php endif; ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="name" value="<?= $editing ? $passenger['name'] : '' ?>" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="<?= $editing ? $passenger['email'] : '' ?>"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" name="phone_number" value="<?= $editing ? $passenger['phone_number'] : '' ?>"
                   pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
            <p class="mt-1 text-sm text-gray-500">Format: 10 digits without spaces or special characters</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <button type="submit" name="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <?= $editing ? 'Update' : 'Add' ?> Passenger
            </button>
            
            <?php if ($editing): ?>
                <a href="passengers.php" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Passengers List -->
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Passengers List</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($passengers_list as $p): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($p['email']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($p['phone_number']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="?edit=<?= $p['passenger_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="passenger_id" value="<?= $p['passenger_id'] ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this passenger?')">
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