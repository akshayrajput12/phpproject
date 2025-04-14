<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Include database connection
require_once '../config/database.php';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Include weather API
require_once '../api/weather.php';

// Get weather data if location is set
$weather_data = null;
$forecast_data = null;
$weather_alerts = [];

if (!empty($user['location'])) {
    $weather_response = getCurrentWeather($user['location']);

    if ($weather_response['status'] === 'success') {
        $weather_data = $weather_response['data'];
        $weather_alerts = generateWeatherAlerts($weather_data);

        // Get forecast
        $forecast_response = getForecast($user['location']);
        if ($forecast_response['status'] === 'success') {
            $forecast_data = $forecast_response['data'];
        }
    }
}

// Include header
include_once '../includes/header.php';
?>

<div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-64 md:min-h-screen">
        <?php include_once '../includes/sidebar.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4">
        <h1 class="text-2xl font-bold mb-6 fade-in">Dashboard</h1>

        <?php if (empty($user['location']) || empty($user['soil_type'])): ?>
            <div class="glass p-6 rounded-lg mb-6 bg-yellow-500/20 border border-yellow-500/50 fade-in">
                <h2 class="text-lg font-semibold mb-2">Complete Your Profile</h2>
                <p class="mb-4">Please update your profile with your location and soil type to get personalized crop recommendations and weather alerts.</p>
                <a href="profile.php" class="inline-block bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                    Update Profile
                </a>
            </div>
        <?php endif; ?>

        <!-- Weather Section -->
        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Current Weather</h2>
                <a href="../pages/weather.php" class="text-accent hover:underline text-sm">View Details</a>
            </div>

            <?php if ($weather_data): ?>
                <div class="flex flex-col md:flex-row items-center">
                    <div class="flex items-center mb-4 md:mb-0 md:mr-6">
                        <img src="<?php echo $weather_data['icon']; ?>" alt="Weather icon" class="w-16 h-16">
                        <div class="ml-4">
                            <div class="text-3xl font-bold"><?php echo $weather_data['temperature']; ?>°C</div>
                            <div class="text-sm opacity-75">Feels like <?php echo $weather_data['feels_like']; ?>°C</div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="text-lg font-medium"><?php echo $weather_data['description']; ?></div>
                        <div class="text-sm opacity-75"><?php echo $weather_data['location']; ?></div>

                        <div class="grid grid-cols-2 gap-2 mt-3">
                            <div class="flex items-center">
                                <i class="fas fa-wind text-blue-400 mr-2"></i>
                                <span><?php echo $weather_data['wind_speed']; ?> m/s</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tint text-blue-400 mr-2"></i>
                                <span><?php echo $weather_data['humidity']; ?>% humidity</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weather Alerts -->
                <?php if (!empty($weather_alerts)): ?>
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <h3 class="font-medium mb-2">Weather Alerts</h3>
                        <div class="space-y-2">
                            <?php foreach ($weather_alerts as $alert): ?>
                                <div class="glass p-3 rounded-lg bg-<?php echo $alert['severity'] === 'high' ? 'red' : ($alert['severity'] === 'medium' ? 'yellow' : 'blue'); ?>-500/20 border border-<?php echo $alert['severity'] === 'high' ? 'red' : ($alert['severity'] === 'medium' ? 'yellow' : 'blue'); ?>-500/50">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle mt-1 mr-2 text-<?php echo $alert['severity'] === 'high' ? 'red' : ($alert['severity'] === 'medium' ? 'yellow' : 'blue'); ?>-400"></i>
                                        <div>
                                            <div class="font-medium"><?php echo ucfirst(str_replace('_', ' ', $alert['type'])); ?> Alert</div>
                                            <p class="text-sm opacity-90"><?php echo $alert['message']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 5-Day Forecast -->
                <?php if ($forecast_data): ?>
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <h3 class="font-medium mb-2">5-Day Forecast</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                            <?php foreach ($forecast_data as $day): ?>
                                <div class="glass p-3 rounded-lg hover-scale">
                                    <div class="text-center">
                                        <div class="font-medium"><?php echo date('D', strtotime($day['day'])); ?></div>
                                        <img src="<?php echo $day['icon']; ?>" alt="Weather icon" class="w-10 h-10 mx-auto">
                                        <div class="text-lg font-bold"><?php echo $day['temperature']; ?>°C</div>
                                        <div class="text-xs opacity-75"><?php echo $day['description']; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="text-center py-6">
                    <i class="fas fa-cloud-sun-rain text-4xl mb-2 text-gray-400"></i>
                    <p>Please set your location in your profile to view weather information.</p>
                    <a href="profile.php" class="inline-block mt-3 bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                        Update Profile
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Crop Recommendations Section -->
        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Crop Recommendations</h2>
                <a href="crops.php" class="text-accent hover:underline text-sm">Manage Crops</a>
            </div>

            <?php if ($weather_data && !empty($user['soil_type'])): ?>
                <div id="crop-recommendations" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="glass p-4 rounded-lg bg-white/5 flex items-center justify-center h-32">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Loading recommendations...</p>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Fetch crop recommendations
                        const weatherData = <?php echo json_encode($weather_data); ?>;
                        const soilType = "<?php echo $user['soil_type']; ?>";
                        const location = "<?php echo $user['location']; ?>";

                        fetch(`../api/gemini.php?action=crop_recommendations&weather=${encodeURIComponent(JSON.stringify(weatherData))}&soil_type=${encodeURIComponent(soilType)}&location=${encodeURIComponent(location)}`)
                            .then(response => response.json())
                            .then(data => {
                                const container = document.getElementById('crop-recommendations');

                                if (data.status === 'success') {
                                    container.innerHTML = '';

                                    data.data.forEach(crop => {
                                        const cropCard = document.createElement('div');
                                        cropCard.className = 'glass p-4 rounded-lg bg-white/5 hover-scale';

                                        cropCard.innerHTML = `
                                            <div class="flex items-start">
                                                <i class="fas fa-seedling text-accent text-xl mt-1 mr-3"></i>
                                                <div>
                                                    <h3 class="font-medium">${crop.name}</h3>
                                                    <p class="text-sm opacity-90 mt-1">${crop.suitability}</p>
                                                    <div class="flex items-center mt-2">
                                                        <span class="text-xs bg-white/10 rounded-full px-2 py-1">${crop.growing_period}</span>
                                                        <button class="ml-auto text-xs bg-accent/80 hover:bg-accent rounded-full px-2 py-1 add-crop" data-crop="${crop.name}">
                                                            <i class="fas fa-plus mr-1"></i> Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        `;

                                        container.appendChild(cropCard);
                                    });

                                    // Add event listeners to "Add" buttons
                                    document.querySelectorAll('.add-crop').forEach(button => {
                                        button.addEventListener('click', function() {
                                            const cropName = this.getAttribute('data-crop');

                                            fetch('../api/crops.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                },
                                                body: JSON.stringify({
                                                    action: 'add_crop',
                                                    crop_name: cropName,
                                                    planting_date: new Date().toISOString().split('T')[0],
                                                    notes: 'Added from recommendations'
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status === 'success') {
                                                    showAlert('Crop added successfully!', 'success');
                                                } else {
                                                    showAlert('Failed to add crop: ' + data.message, 'error');
                                                }
                                            })
                                            .catch(error => {
                                                showAlert('Error: ' + error.message, 'error');
                                            });
                                        });
                                    });
                                } else {
                                    container.innerHTML = `
                                        <div class="col-span-2 text-center py-4">
                                            <i class="fas fa-exclamation-circle text-2xl mb-2 text-red-400"></i>
                                            <p>Failed to load crop recommendations.</p>
                                            <p class="text-sm opacity-75 mt-1">${data.message || 'Unknown error'}</p>
                                        </div>
                                    `;
                                }
                            })
                            .catch(error => {
                                const container = document.getElementById('crop-recommendations');
                                container.innerHTML = `
                                    <div class="col-span-2 text-center py-4">
                                        <i class="fas fa-exclamation-circle text-2xl mb-2 text-red-400"></i>
                                        <p>Failed to load crop recommendations.</p>
                                        <p class="text-sm opacity-75 mt-1">${error.message}</p>
                                    </div>
                                `;
                            });
                    });
                </script>
            <?php else: ?>
                <div class="text-center py-6">
                    <i class="fas fa-leaf text-4xl mb-2 text-gray-400"></i>
                    <p>Please set your location and soil type in your profile to get crop recommendations.</p>
                    <a href="profile.php" class="inline-block mt-3 bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                        Update Profile
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pest Warnings Section -->
        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <h2 class="text-lg font-semibold mb-4">Pest & Disease Warnings</h2>

            <?php if ($weather_data && !empty($user['soil_type'])): ?>
                <!-- Get user's crops -->
                <?php
                $stmt = $conn->prepare("SELECT crop_name FROM crops WHERE user_id = ? LIMIT 1");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user_crop = $result->num_rows > 0 ? $result->fetch_assoc()['crop_name'] : null;
                $stmt->close();
                ?>

                <?php if ($user_crop): ?>
                    <div id="pest-warnings" class="space-y-3">
                        <div class="glass p-4 rounded-lg bg-white/5 flex items-center justify-center h-32">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                <p>Loading pest warnings for <?php echo $user_crop; ?>...</p>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Fetch pest warnings
                            const weatherData = <?php echo json_encode($weather_data); ?>;
                            const cropName = "<?php echo $user_crop; ?>";
                            const soilType = "<?php echo $user['soil_type']; ?>";

                            fetch(`../api/gemini.php?action=pest_warnings&weather=${encodeURIComponent(JSON.stringify(weatherData))}&crop_name=${encodeURIComponent(cropName)}&soil_type=${encodeURIComponent(soilType)}`)
                                .then(response => response.json())
                                .then(data => {
                                    const container = document.getElementById('pest-warnings');

                                    if (data.status === 'success') {
                                        container.innerHTML = '';

                                        data.data.forEach(pest => {
                                            const pestCard = document.createElement('div');
                                            pestCard.className = 'glass p-4 rounded-lg bg-white/5';

                                            pestCard.innerHTML = `
                                                <div class="flex items-start">
                                                    <i class="fas fa-bug text-red-400 text-xl mt-1 mr-3"></i>
                                                    <div>
                                                        <h3 class="font-medium">${pest.name}</h3>
                                                        <p class="text-sm opacity-90 mt-1">${pest.risk_factors}</p>
                                                        <div class="mt-2">
                                                            <div class="text-xs font-medium mb-1">Warning Signs:</div>
                                                            <p class="text-xs opacity-90">${pest.warning_signs}</p>
                                                        </div>
                                                        <div class="mt-2">
                                                            <div class="text-xs font-medium mb-1">Prevention:</div>
                                                            <p class="text-xs opacity-90">${pest.prevention}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;

                                            container.appendChild(pestCard);
                                        });
                                    } else {
                                        container.innerHTML = `
                                            <div class="text-center py-4">
                                                <i class="fas fa-exclamation-circle text-2xl mb-2 text-red-400"></i>
                                                <p>Failed to load pest warnings.</p>
                                                <p class="text-sm opacity-75 mt-1">${data.message || 'Unknown error'}</p>
                                            </div>
                                        `;
                                    }
                                })
                                .catch(error => {
                                    const container = document.getElementById('pest-warnings');
                                    container.innerHTML = `
                                        <div class="text-center py-4">
                                            <i class="fas fa-exclamation-circle text-2xl mb-2 text-red-400"></i>
                                            <p>Failed to load pest warnings.</p>
                                            <p class="text-sm opacity-75 mt-1">${error.message}</p>
                                        </div>
                                    `;
                                });
                        });
                    </script>
                <?php else: ?>
                    <div class="text-center py-6">
                        <i class="fas fa-bug text-4xl mb-2 text-gray-400"></i>
                        <p>Add crops to your profile to get pest and disease warnings.</p>
                        <a href="crops.php" class="inline-block mt-3 bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                            Manage Crops
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-6">
                    <i class="fas fa-bug text-4xl mb-2 text-gray-400"></i>
                    <p>Please set your location and soil type in your profile to get pest warnings.</p>
                    <a href="profile.php" class="inline-block mt-3 bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                        Update Profile
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
