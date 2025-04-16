<div class="glass min-h-full p-4 md:fixed md:w-64">
    <div class="flex flex-col h-full">
        <div class="flex items-center mb-6">
            <i class="fas fa-user-shield text-accent text-2xl mr-3"></i>
            <h2 class="text-xl font-semibold">Admin Panel</h2>
        </div>
        
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="index.php" class="flex items-center p-2 rounded-lg hover:bg-white/10 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-white/10' : ''; ?>">
                        <i class="fas fa-tachometer-alt w-6 text-accent"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="users.php" class="flex items-center p-2 rounded-lg hover:bg-white/10 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-white/10' : ''; ?>">
                        <i class="fas fa-users w-6 text-accent"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="crops.php" class="flex items-center p-2 rounded-lg hover:bg-white/10 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'crops.php' ? 'bg-white/10' : ''; ?>">
                        <i class="fas fa-seedling w-6 text-accent"></i>
                        <span>Crops</span>
                    </a>
                </li>
                <li>
                    <a href="statistics.php" class="flex items-center p-2 rounded-lg hover:bg-white/10 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'statistics.php' ? 'bg-white/10' : ''; ?>">
                        <i class="fas fa-chart-bar w-6 text-accent"></i>
                        <span>Statistics</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="mt-auto pt-4 border-t border-white/10">
            <a href="../select.php" class="flex items-center p-2 rounded-lg hover:bg-white/10 transition-colors">
                <i class="fas fa-exchange-alt w-6 text-accent"></i>
                <span>Switch Dashboard</span>
            </a>
            <a href="../../auth/logout.php" class="flex items-center p-2 rounded-lg hover:bg-white/10 transition-colors text-red-400">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>
