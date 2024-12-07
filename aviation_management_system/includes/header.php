<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default values for role checks
$is_admin = false;
$is_supplier = false;
$is_doctor = false;

// Only check roles if session exists
if (isset($_SESSION['role'])) {
    $is_admin = ($_SESSION['role'] === 'Administrator');
    $is_supplier = ($_SESSION['role'] === 'Supplier');
    $is_doctor = ($_SESSION['role'] === 'Doctor');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviation Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-8">
                    <a href="..\page\index.html" class="flex items-center py-4 px-2">
                        <i class="fas fa-plane text-2xl mr-3 transform -rotate-45"></i>
                        <span class="font-bold text-xl">Aeronix</span>
                    </a>
                    
                    <!-- Main Navigation -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="flights.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-plane-departure mr-2"></i>
                            Flights
                        </a>
                        <a href="routes.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-route mr-2"></i>
                            Routes
                        </a>
                        <a href="aircraft.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-plane mr-2"></i>
                            Aircraft
                        </a>
                        <a href="airports.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-building mr-2"></i>
                            Airports
                        </a>
                        <a href="passengers.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Passengers
                        </a>
                        <a href="bookings.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            Bookings
                        </a>
                        <!--
                         <a href="baggage.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-suitcase mr-2"></i>
                            Baggage
                        </a> 
                         <a href="maintenance.php" class="py-4 px-3 text-white hover:bg-blue-700 transition duration-300 rounded-md flex items-center">
                            <i class="fas fa-wrench mr-2"></i>
                            Maintenance
                        </a> -->
                    </div>
                </div>

                <!-- User Menu
                <div class="flex items-center space-x-3">
                    <div class="relative group">
                        <button class="flex items-center space-x-2 py-2 px-3 rounded-md hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span class="hidden md:inline">Account</span>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>
                        <div class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-xl hidden group-hover:block">
                            <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <a href="settings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <hr class="my-2">
                            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button class="mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu hidden md:hidden">
            <!-- Add your mobile menu items here -->
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
    <!-- Page content will go here -->

<script>
// Mobile menu toggle
document.querySelector('.mobile-menu-button').addEventListener('click', function() {
    document.querySelector('.mobile-menu').classList.toggle('hidden');
});
</script>
</body>
</html>
