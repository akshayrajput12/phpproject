<?php
// Start session
session_start();

// Include database connection
require_once '../config/database.php';

// Include weather API
require_once '../api/weather.php';

// Get user location if logged in
$user_location = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT location FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_location = $user['location'] ?? '';
    }
    $stmt->close();
}

// Get location from query parameter or use user's location
$location = isset($_GET['location']) ? sanitize($_GET['location']) : $user_location;

// Get weather data if location is set
$weather_data = null;
$forecast_data = null;
$weather_alerts = [];

if (!empty($location)) {
    $weather_response = getCurrentWeather($location);

    if ($weather_response['status'] === 'success') {
        $weather_data = $weather_response['data'];
        $weather_alerts = generateWeatherAlerts($weather_data);

        // Get forecast
        $forecast_response = getForecast($location);
        if ($forecast_response['status'] === 'success') {
            $forecast_data = $forecast_response['data'];
        }
    }
}

// Include header
include_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center fade-in">Weather Information</h1>

    <!-- Search Form -->
    <div class="glass p-6 rounded-lg mb-8 max-w-xl mx-auto fade-in">
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="location" class="block text-sm font-medium mb-2">Location</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                    </div>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Enter city name" required>
                </div>
            </div>

            <div class="self-end">
                <button type="submit" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300 hover-scale h-10">
                    <i class="fas fa-search mr-2"></i> Get Weather
                </button>
            </div>
        </form>
    </div>

    <?php if ($weather_data): ?>
        <!-- Current Weather -->
        <div class="glass p-6 rounded-lg mb-8 max-w-4xl mx-auto fade-in">
            <h2 class="text-xl font-semibold mb-6 text-center">Current Weather in <?php echo $weather_data['location']; ?></h2>

            <div class="flex flex-col md:flex-row items-center justify-center">
                <div class="flex flex-col items-center mb-6 md:mb-0 md:mr-12">
                    <img src="<?php echo $weather_data['icon']; ?>" alt="Weather icon" class="w-32 h-32">
                    <div class="text-4xl font-bold mt-2"><?php echo $weather_data['temperature']; ?>°C</div>
                    <div class="text-lg opacity-75">Feels like <?php echo $weather_data['feels_like']; ?>°C</div>
                    <div class="text-xl mt-2"><?php echo $weather_data['description']; ?></div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="flex flex-col items-center glass p-4 rounded-lg bg-white/5 hover-scale">
                        <i class="fas fa-wind text-blue-400 text-2xl mb-2"></i>
                        <div class="text-sm opacity-75">Wind Speed</div>
                        <div class="text-xl font-medium"><?php echo $weather_data['wind_speed']; ?> m/s</div>
                    </div>

                    <div class="flex flex-col items-center glass p-4 rounded-lg bg-white/5 hover-scale">
                        <i class="fas fa-tint text-blue-400 text-2xl mb-2"></i>
                        <div class="text-sm opacity-75">Humidity</div>
                        <div class="text-xl font-medium"><?php echo $weather_data['humidity']; ?>%</div>
                    </div>

                    <div class="flex flex-col items-center glass p-4 rounded-lg bg-white/5 hover-scale">
                        <i class="fas fa-clock text-blue-400 text-2xl mb-2"></i>
                        <div class="text-sm opacity-75">Updated</div>
                        <div class="text-xl font-medium"><?php echo date('H:i', $weather_data['timestamp']); ?></div>
                    </div>

                    <div class="flex flex-col items-center glass p-4 rounded-lg bg-white/5 hover-scale">
                        <i class="fas fa-calendar-day text-blue-400 text-2xl mb-2"></i>
                        <div class="text-sm opacity-75">Date</div>
                        <div class="text-xl font-medium"><?php echo date('M j', $weather_data['timestamp']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Weather Alerts -->
            <?php if (!empty($weather_alerts)): ?>
                <div class="mt-8 pt-6 border-t border-white/10">
                    <h3 class="text-lg font-medium mb-4">Weather Alerts</h3>
                    <div class="space-y-3">
                        <?php foreach ($weather_alerts as $alert): ?>
                            <div class="glass p-4 rounded-lg bg-<?php echo $alert['severity'] === 'high' ? 'red' : ($alert['severity'] === 'medium' ? 'yellow' : 'blue'); ?>-500/20 border border-<?php echo $alert['severity'] === 'high' ? 'red' : ($alert['severity'] === 'medium' ? 'yellow' : 'blue'); ?>-500/50">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle mt-1 mr-3 text-<?php echo $alert['severity'] === 'high' ? 'red' : ($alert['severity'] === 'medium' ? 'yellow' : 'blue'); ?>-400"></i>
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
        </div>

        <!-- 5-Day Forecast -->
        <?php if ($forecast_data): ?>
            <div class="glass p-6 rounded-lg mb-8 max-w-4xl mx-auto fade-in">
                <h2 class="text-xl font-semibold mb-6 text-center">5-Day Forecast</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                    <?php foreach ($forecast_data as $day): ?>
                        <div class="glass p-4 rounded-lg bg-white/5 hover-scale">
                            <div class="flex flex-col items-center">
                                <div class="font-medium text-lg"><?php echo date('D', strtotime($day['day'])); ?></div>
                                <div class="text-sm opacity-75"><?php echo date('M j', strtotime($day['date'])); ?></div>
                                <img src="<?php echo $day['icon']; ?>" alt="Weather icon" class="w-16 h-16 my-2">
                                <div class="text-2xl font-bold"><?php echo $day['temperature']; ?>°C</div>
                                <div class="text-sm opacity-90 text-center mt-1"><?php echo $day['description']; ?></div>

                                <div class="w-full mt-3 pt-3 border-t border-white/10 grid grid-cols-2 gap-2 text-center">
                                    <div>
                                        <i class="fas fa-tint text-blue-400 text-sm"></i>
                                        <div class="text-xs"><?php echo $day['humidity']; ?>%</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-wind text-blue-400 text-sm"></i>
                                        <div class="text-xs"><?php echo $day['wind_speed']; ?> m/s</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Farming Tips Based on Weather -->
        <div class="glass p-6 rounded-lg mb-8 max-w-4xl mx-auto fade-in">
            <h2 class="text-xl font-semibold mb-4">Farming Tips Based on Current Weather</h2>

            <div class="space-y-4">
                <?php
                // Generate tips based on weather conditions
                $tips = [];

                if ($weather_data['temperature'] > 30) {
                    $tips[] = [
                        'icon' => 'fa-temperature-high',
                        'title' => 'High Temperature',
                        'content' => 'Water plants early in the morning or late in the evening to reduce evaporation. Consider providing shade for sensitive crops.'
                    ];
                } elseif ($weather_data['temperature'] < 10) {
                    $tips[] = [
                        'icon' => 'fa-temperature-low',
                        'title' => 'Low Temperature',
                        'content' => 'Protect sensitive plants from frost with covers. Avoid watering plants in the evening as it may lead to frost damage.'
                    ];
                }

                if ($weather_data['humidity'] > 80) {
                    $tips[] = [
                        'icon' => 'fa-tint',
                        'title' => 'High Humidity',
                        'content' => 'Monitor plants for fungal diseases. Ensure good air circulation around plants and avoid overhead watering.'
                    ];
                } elseif ($weather_data['humidity'] < 40) {
                    $tips[] = [
                        'icon' => 'fa-tint-slash',
                        'title' => 'Low Humidity',
                        'content' => 'Increase watering frequency. Consider mulching to retain soil moisture and protect plant roots.'
                    ];
                }

                if ($weather_data['wind_speed'] > 8) {
                    $tips[] = [
                        'icon' => 'fa-wind',
                        'title' => 'Strong Winds',
                        'content' => 'Provide support for tall plants and young saplings. Avoid spraying pesticides or fertilizers as they may drift.'
                    ];
                }

                if (in_array($weather_data['weather'], ['Rain', 'Drizzle', 'Thunderstorm'])) {
                    $tips[] = [
                        'icon' => 'fa-cloud-rain',
                        'title' => 'Rainy Conditions',
                        'content' => 'Check drainage systems to prevent waterlogging. Delay fertilizer application to prevent runoff and nutrient loss.'
                    ];
                } elseif (in_array($weather_data['weather'], ['Clear', 'Sunny'])) {
                    $tips[] = [
                        'icon' => 'fa-sun',
                        'title' => 'Sunny Conditions',
                        'content' => 'Ideal time for harvesting crops and drying seeds. Ensure adequate watering, especially for newly planted crops.'
                    ];
                }

                // Add a general tip
                $tips[] = [
                    'icon' => 'fa-seedling',
                    'title' => 'General Tip',
                    'content' => 'Regularly monitor soil moisture levels and adjust watering schedules based on weather conditions and plant needs.'
                ];

                foreach ($tips as $tip):
                ?>
                    <div class="glass p-4 rounded-lg bg-white/5 hover-scale">
                        <div class="flex items-start">
                            <i class="fas <?php echo $tip['icon']; ?> text-accent text-xl mt-1 mr-3"></i>
                            <div>
                                <h3 class="font-medium"><?php echo $tip['title']; ?></h3>
                                <p class="text-sm opacity-90 mt-1"><?php echo $tip['content']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php else: ?>
        <!-- No Weather Data -->
        <div class="glass p-8 rounded-lg text-center max-w-2xl mx-auto fade-in">
            <?php if (empty($location)): ?>
                <i class="fas fa-cloud-sun-rain text-5xl mb-4 text-gray-400"></i>
                <h2 class="text-xl font-semibold mb-2">Enter a Location</h2>
                <p class="opacity-75 mb-4">Please enter a city name to get weather information.</p>
            <?php else: ?>
                <i class="fas fa-exclamation-circle text-5xl mb-4 text-red-400"></i>
                <h2 class="text-xl font-semibold mb-2">Weather Data Not Available</h2>
                <p class="opacity-75 mb-4">Could not fetch weather data for "<?php echo htmlspecialchars($location); ?>". Please check the location name and try again.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Weather Information -->
    <div class="glass p-6 rounded-lg max-w-4xl mx-auto fade-in">
        <h2 class="text-xl font-semibold mb-4">Weather and Farming</h2>

        <div class="prose prose-invert max-w-none opacity-90">
            <p>Weather plays a crucial role in agriculture, affecting everything from planting decisions to harvest timing. Understanding weather patterns and forecasts can help farmers make informed decisions and mitigate risks.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="glass p-4 rounded-lg bg-white/5">
                    <h3 class="text-lg font-medium mb-2">Temperature</h3>
                    <p class="text-sm">Temperature affects plant growth rates, flowering, and fruiting. Each crop has optimal temperature ranges for different growth stages. Extreme temperatures can stress plants and reduce yields.</p>
                </div>

                <div class="glass p-4 rounded-lg bg-white/5">
                    <h3 class="text-lg font-medium mb-2">Precipitation</h3>
                    <p class="text-sm">Rainfall is essential for crop growth, but too much or too little can be detrimental. Monitoring precipitation helps with irrigation planning and flood prevention.</p>
                </div>

                <div class="glass p-4 rounded-lg bg-white/5">
                    <h3 class="text-lg font-medium mb-2">Humidity</h3>
                    <p class="text-sm">High humidity can increase the risk of fungal diseases, while low humidity can increase water requirements. Balancing humidity is important for plant health.</p>
                </div>

                <div class="glass p-4 rounded-lg bg-white/5">
                    <h3 class="text-lg font-medium mb-2">Wind</h3>
                    <p class="text-sm">Wind affects pollination, evaporation rates, and can cause physical damage to crops. Understanding wind patterns helps with windbreak planning and spray timing.</p>
                </div>
            </div>

            <p class="mt-6">By staying informed about weather conditions and forecasts, farmers can adapt their practices to maximize yields and minimize losses. Our weather service provides accurate, up-to-date information to help you make the best decisions for your farm.</p>
        </div>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
