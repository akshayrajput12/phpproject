<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../../config/database.php';

// Get total number of users
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
$stmt->execute();
$total_users = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Get total number of crops
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM crops");
$stmt->execute();
$total_crops = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Get user registrations by month (last 6 months)
$stmt = $conn->prepare("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as count
    FROM users
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC
");
$stmt->execute();
$result = $stmt->get_result();
$user_registrations = [];
while ($row = $result->fetch_assoc()) {
    $user_registrations[] = $row;
}
$stmt->close();

// Get crop additions by month (last 6 months)
$stmt = $conn->prepare("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as count
    FROM crops
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC
");
$stmt->execute();
$result = $stmt->get_result();
$crop_additions = [];
while ($row = $result->fetch_assoc()) {
    $crop_additions[] = $row;
}
$stmt->close();

// Get top 5 most popular crops
$stmt = $conn->prepare("
    SELECT 
        crop_name,
        COUNT(*) as count
    FROM crops
    GROUP BY crop_name
    ORDER BY count DESC
    LIMIT 5
");
$stmt->execute();
$result = $stmt->get_result();
$popular_crops = [];
while ($row = $result->fetch_assoc()) {
    $popular_crops[] = $row;
}
$stmt->close();

// Include header
include_once '../../includes/header.php';
?>

<div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-64 md:min-h-screen">
        <?php include_once 'sidebar.php'; ?>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 p-4">
        <h1 class="text-2xl font-bold mb-6 fade-in">Statistics</h1>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="glass p-6 rounded-lg fade-in">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                        <i class="fas fa-users text-accent"></i>
                    </div>
                    <div>
                        <h2 class="text-sm opacity-75">Total Users</h2>
                        <p class="text-2xl font-bold"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="glass p-6 rounded-lg fade-in">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                        <i class="fas fa-seedling text-accent"></i>
                    </div>
                    <div>
                        <h2 class="text-sm opacity-75">Total Crops</h2>
                        <p class="text-2xl font-bold"><?php echo $total_crops; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- User Registrations Chart -->
            <div class="glass p-6 rounded-lg fade-in">
                <h2 class="text-lg font-semibold mb-4">User Registrations (Last 6 Months)</h2>
                <div class="h-64">
                    <canvas id="userRegistrationsChart"></canvas>
                </div>
            </div>
            
            <!-- Crop Additions Chart -->
            <div class="glass p-6 rounded-lg fade-in">
                <h2 class="text-lg font-semibold mb-4">Crop Additions (Last 6 Months)</h2>
                <div class="h-64">
                    <canvas id="cropAdditionsChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Popular Crops -->
        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <h2 class="text-lg font-semibold mb-4">Most Popular Crops</h2>
            <div class="h-64">
                <canvas id="popularCropsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Registrations Chart
        const userRegistrationsCtx = document.getElementById('userRegistrationsChart').getContext('2d');
        const userRegistrationsData = <?php echo json_encode($user_registrations); ?>;
        
        const userRegistrationsLabels = userRegistrationsData.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month - 1).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        });
        
        const userRegistrationsValues = userRegistrationsData.map(item => item.count);
        
        new Chart(userRegistrationsCtx, {
            type: 'line',
            data: {
                labels: userRegistrationsLabels,
                datasets: [{
                    label: 'User Registrations',
                    data: userRegistrationsValues,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Crop Additions Chart
        const cropAdditionsCtx = document.getElementById('cropAdditionsChart').getContext('2d');
        const cropAdditionsData = <?php echo json_encode($crop_additions); ?>;
        
        const cropAdditionsLabels = cropAdditionsData.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month - 1).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        });
        
        const cropAdditionsValues = cropAdditionsData.map(item => item.count);
        
        new Chart(cropAdditionsCtx, {
            type: 'line',
            data: {
                labels: cropAdditionsLabels,
                datasets: [{
                    label: 'Crop Additions',
                    data: cropAdditionsValues,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Popular Crops Chart
        const popularCropsCtx = document.getElementById('popularCropsChart').getContext('2d');
        const popularCropsData = <?php echo json_encode($popular_crops); ?>;
        
        const popularCropsLabels = popularCropsData.map(item => item.crop_name);
        const popularCropsValues = popularCropsData.map(item => item.count);
        
        new Chart(popularCropsCtx, {
            type: 'bar',
            data: {
                labels: popularCropsLabels,
                datasets: [{
                    label: 'Number of Users',
                    data: popularCropsValues,
                    backgroundColor: [
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(249, 115, 22, 0.7)',
                        'rgba(236, 72, 153, 0.7)'
                    ],
                    borderColor: [
                        'rgba(139, 92, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(236, 72, 153, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>

<?php
// Include footer
include_once '../../includes/footer.php';
?>
