<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="glass rounded-lg overflow-hidden w-full md:w-64 h-auto md:h-screen flex-shrink-0">
    <div class="p-4 border-b border-white/10">
        <div class="flex items-center space-x-3">
            <i class="fas fa-seedling text-accent text-2xl"></i>
            <div>
                <h2 class="text-lg font-semibold">Farmer's Friend</h2>
                <p class="text-xs opacity-75">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>
        </div>
    </div>
    
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="index.php" class="flex items-center p-2 rounded-lg hover-scale transition-all duration-300 <?php echo $current_page === 'index.php' ? 'bg-white/10 text-accent' : 'hover:bg-white/5'; ?>">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="profile.php" class="flex items-center p-2 rounded-lg hover-scale transition-all duration-300 <?php echo $current_page === 'profile.php' ? 'bg-white/10 text-accent' : 'hover:bg-white/5'; ?>">
                    <i class="fas fa-user w-6"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="crops.php" class="flex items-center p-2 rounded-lg hover-scale transition-all duration-300 <?php echo $current_page === 'crops.php' ? 'bg-white/10 text-accent' : 'hover:bg-white/5'; ?>">
                    <i class="fas fa-leaf w-6"></i>
                    <span>My Crops</span>
                </a>
            </li>
            <li>
                <a href="../pages/weather.php" class="flex items-center p-2 rounded-lg hover-scale transition-all duration-300 <?php echo $current_page === 'weather.php' ? 'bg-white/10 text-accent' : 'hover:bg-white/5'; ?>">
                    <i class="fas fa-cloud-sun w-6"></i>
                    <span>Weather</span>
                </a>
            </li>
            <li class="border-t border-white/10 pt-2 mt-4">
                <a href="../auth/logout.php" class="flex items-center p-2 rounded-lg text-red-400 hover:bg-red-500/20 hover-scale transition-all duration-300">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="p-4 mt-auto border-t border-white/10">
        <div class="glass rounded-lg p-3 bg-accent/10">
            <h3 class="font-medium text-sm">Weather Alert</h3>
            <p class="text-xs mt-1 opacity-75">
                <i class="fas fa-exclamation-triangle mr-1 text-yellow-400"></i>
                Check today's weather forecast for any alerts
            </p>
        </div>
    </div>
</div>
