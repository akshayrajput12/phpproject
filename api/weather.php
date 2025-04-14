<?php
// Include database connection
require_once '../config/database.php';

// Weather API key
define('WEATHER_API_KEY', 'e193032dded944e3805151029251202');
define('WEATHER_API_URL', 'http://api.weatherapi.com/v1');

// Use the defined constants
$api_key = WEATHER_API_KEY;

/**
 * Get current weather data for a location
 *
 * @param string $location City name or coordinates
 * @return array Weather data
 */
function getCurrentWeather($location) {
    global $api_key;

    // Encode location for URL
    $location = urlencode($location);

    // API endpoint
    $url = WEATHER_API_URL . "/current.json?key={$api_key}&q={$location}&aqi=no";

    // Make API request
    $response = file_get_contents($url);

    if ($response === false) {
        return [
            'status' => 'error',
            'message' => 'Failed to fetch weather data'
        ];
    }

    // Decode JSON response
    $data = json_decode($response, true);

    if (isset($data['cod']) && $data['cod'] != 200) {
        return [
            'status' => 'error',
            'message' => $data['message'] ?? 'Unknown error'
        ];
    }

    // Format and return weather data
    return [
        'status' => 'success',
        'data' => [
            'location' => $data['location']['name'] . ', ' . $data['location']['country'],
            'temperature' => round($data['current']['temp_c']),
            'feels_like' => round($data['current']['feelslike_c']),
            'humidity' => $data['current']['humidity'],
            'wind_speed' => $data['current']['wind_kph'] / 3.6, // Convert km/h to m/s
            'weather' => $data['current']['condition']['text'],
            'description' => $data['current']['condition']['text'],
            'icon' => $data['current']['condition']['icon'],
            'timestamp' => strtotime($data['current']['last_updated'])
        ]
    ];
}

/**
 * Get 5-day weather forecast for a location
 *
 * @param string $location City name or coordinates
 * @return array Forecast data
 */
function getForecast($location) {
    global $api_key;

    // Encode location for URL
    $location = urlencode($location);

    // API endpoint
    $url = WEATHER_API_URL . "/forecast.json?key={$api_key}&q={$location}&days=5&aqi=no&alerts=no";

    // Make API request
    $response = file_get_contents($url);

    if ($response === false) {
        return [
            'status' => 'error',
            'message' => 'Failed to fetch forecast data'
        ];
    }

    // Decode JSON response
    $data = json_decode($response, true);

    if (isset($data['cod']) && $data['cod'] != 200) {
        return [
            'status' => 'error',
            'message' => $data['message'] ?? 'Unknown error'
        ];
    }

    // Process forecast data
    $forecast = [];

    if (isset($data['forecast']['forecastday'])) {
        foreach ($data['forecast']['forecastday'] as $day) {
            $forecast[] = [
                'date' => $day['date'],
                'day' => date('l', strtotime($day['date'])),
                'temperature' => round($day['day']['avgtemp_c']),
                'weather' => $day['day']['condition']['text'],
                'description' => $day['day']['condition']['text'],
                'icon' => $day['day']['condition']['icon'],
                'humidity' => $day['day']['avghumidity'],
                'wind_speed' => $day['day']['maxwind_kph'] / 3.6 // Convert km/h to m/s
            ];
        }
    }

    return [
        'status' => 'success',
        'data' => $forecast
    ];
}

/**
 * Generate weather alerts based on weather conditions
 *
 * @param array $weather Current weather data
 * @return array Weather alerts
 */
function generateWeatherAlerts($weather) {
    $alerts = [];

    // Check for extreme temperatures
    if ($weather['temperature'] > 35) {
        $alerts[] = [
            'type' => 'extreme_heat',
            'severity' => 'high',
            'message' => 'Extreme heat warning. Ensure crops have adequate water and consider providing shade for sensitive plants.'
        ];
    } elseif ($weather['temperature'] < 5) {
        $alerts[] = [
            'type' => 'frost',
            'severity' => 'high',
            'message' => 'Frost warning. Protect sensitive crops with covers or bring potted plants indoors.'
        ];
    }

    // Check for rain/storms
    if (in_array($weather['weather'], ['Rain', 'Thunderstorm'])) {
        $alerts[] = [
            'type' => 'rain',
            'severity' => 'medium',
            'message' => 'Heavy rain expected. Check drainage systems and protect young seedlings.'
        ];
    }

    // Check for strong winds
    if ($weather['wind_speed'] > 10) {
        $alerts[] = [
            'type' => 'wind',
            'severity' => 'medium',
            'message' => 'Strong winds expected. Secure structures and provide support for tall plants.'
        ];
    }

    // Check for high humidity
    if ($weather['humidity'] > 85) {
        $alerts[] = [
            'type' => 'humidity',
            'severity' => 'low',
            'message' => 'High humidity levels. Monitor for fungal diseases and ensure good air circulation around plants.'
        ];
    }

    return $alerts;
}

// API endpoint to get weather data
if (isset($_GET['action']) && isset($_GET['location'])) {
    $location = sanitize($_GET['location']);
    $action = sanitize($_GET['action']);

    header('Content-Type: application/json');

    switch ($action) {
        case 'current':
            echo json_encode(getCurrentWeather($location));
            break;
        case 'forecast':
            echo json_encode(getForecast($location));
            break;
        default:
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid action'
            ]);
    }

    exit;
}
?>
