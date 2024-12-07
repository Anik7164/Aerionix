    </div>
    <footer class="bg-blue-600 text-white mt-auto">
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="animate__animated animate__fadeIn">
                    <h3 class="text-lg font-semibold mb-4">Aeronix</h3>
                    <p class="text-sm text-gray-100">
                        Your comprehensive aviation management solution for efficient flight operations and passenger services.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Flight Services</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="flights.php" class="hover:text-gray-200 transition">Flight Schedule</a></li>
                        <li><a href="bookings.php" class="hover:text-gray-200 transition">Book a Flight</a></li>
                        <li><a href="baggage.php" class="hover:text-gray-200 transition">Baggage Info</a></li>
                        <li><a href="routes.php" class="hover:text-gray-200 transition">Routes Network</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>support@aeronix.com</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Terminal 1, International Airport</span>
                        </li>
                    </ul>
                </div>

                <!-- Operations -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Operations</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span><?php echo number_format($stats['flights'] ?? 0); ?> Active Flights</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span><?php echo number_format($stats['passengers'] ?? 0); ?> Passengers Served</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                            </svg>
                            <span><?php echo number_format($stats['aircraft'] ?? 0); ?> Aircraft Fleet</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-blue-500 mt-8 pt-8 text-center text-sm">
                <p>&copy; <?php echo date('Y'); ?> Aeronix - Aviation Management System. All rights reserved.</p>
                <?php if (isset($_SESSION['last_login'])): ?>
                <p class="mt-2 text-sm text-gray-300">
                    Last login: <?php echo date('Y-m-d H:i:s', $_SESSION['last_login']); ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <!-- Add any JavaScript files here -->
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html> 