<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit;
}

// Include header
include_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8 text-center fade-in">Dashboard Selection</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <!-- User Dashboard Card -->
        <div class="glass glass-enhanced p-8 rounded-xl hover-scale tilt-effect">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                    <i class="fas fa-user text-accent text-3xl"></i>
                </div>
                <h2 class="text-2xl font-semibold mb-4">User Dashboard</h2>
                <p class="opacity-75 mb-6">Access your personal dashboard with crop recommendations, weather updates, and pest warnings.</p>
                <a href="index.php" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-medium rounded-lg text-lg px-6 py-3 transition-all duration-300 hover-scale magnetic-effect">
                    Go to User Dashboard
                </a>
            </div>
        </div>
        
        <!-- Admin Dashboard Card -->
        <div class="glass glass-enhanced p-8 rounded-xl hover-scale tilt-effect">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                    <i class="fas fa-user-shield text-accent text-3xl"></i>
                </div>
                <h2 class="text-2xl font-semibold mb-4">Admin Dashboard</h2>
                <p class="opacity-75 mb-6">Manage users, view statistics, and access administrative functions for the platform.</p>
                <a href="admin/index.php" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-medium rounded-lg text-lg px-6 py-3 transition-all duration-300 hover-scale magnetic-effect">
                    Go to Admin Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
