<?php
// Include database connection
require_once '../config/database.php';

// Gemini API key
define('GEMINI_API_KEY', 'AIzaSyBaop94QTnoPuBIgd_-swirOs2LlYgy8NI');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');

// Use the defined constants
$api_key = GEMINI_API_KEY;

/**
 * Get crop recommendations based on weather and soil type
 *
 * @param array $weather Current weather data
 * @param string $soil_type Soil type
 * @param string $location Location
 * @return array Crop recommendations
 */
function getCropRecommendations($weather, $soil_type, $location) {
    global $api_key;

    // API endpoint
    $url = GEMINI_API_URL . "?key={$api_key}";

    // Prepare prompt for Gemini
    $prompt = "As an agricultural expert, recommend 5 suitable crops for farming based on the following conditions:
    - Location: {$location}
    - Current weather: {$weather['description']} at {$weather['temperature']}°C
    - Humidity: {$weather['humidity']}%
    - Wind speed: {$weather['wind_speed']} m/s
    - Soil type: {$soil_type}

    For each crop, provide:
    1. Crop name
    2. Why it's suitable for these conditions
    3. Basic care instructions
    4. Expected growing period
    5. Potential challenges

    Format the response as JSON with the following structure:
    {
      \"crops\": [
        {
          \"name\": \"Crop Name\",
          \"suitability\": \"Why it's suitable\",
          \"care\": \"Basic care instructions\",
          \"growing_period\": \"Expected growing period\",
          \"challenges\": \"Potential challenges\"
        }
      ]
    }";

    // Prepare request data
    $data = [
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => $prompt
                    ]
                ]
            ]
        ]
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        curl_close($ch);
        return [
            'status' => 'error',
            'message' => 'Failed to connect to Gemini API: ' . curl_error($ch)
        ];
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $result = json_decode($response, true);

    // Extract the text content from Gemini's response
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $text = $result['candidates'][0]['content']['parts'][0]['text'];

        // Try to extract JSON from the response
        preg_match('/\{.*\}/s', $text, $matches);

        if (!empty($matches)) {
            $json_str = $matches[0];
            $recommendations = json_decode($json_str, true);

            if ($recommendations && isset($recommendations['crops'])) {
                return [
                    'status' => 'success',
                    'data' => $recommendations['crops']
                ];
            }
        }

        // Fallback if JSON extraction fails
        return [
            'status' => 'error',
            'message' => 'Failed to parse Gemini response',
            'raw_response' => $text
        ];
    }

    return [
        'status' => 'error',
        'message' => 'Invalid response from Gemini API',
        'raw_response' => $response
    ];
}

/**
 * Get pest warnings based on weather, crop, and soil type
 *
 * @param array $weather Current weather data
 * @param string $crop_name Crop name
 * @param string $soil_type Soil type
 * @return array Pest warnings
 */
function getPestWarnings($weather, $crop_name, $soil_type) {
    global $api_key;

    // API endpoint
    $url = GEMINI_API_URL . "?key={$api_key}";

    // Prepare prompt for Gemini
    $prompt = "As an agricultural pest expert, identify 3 potential pest or disease risks for {$crop_name} based on the following conditions:
    - Current weather: {$weather['description']} at {$weather['temperature']}°C
    - Humidity: {$weather['humidity']}%
    - Wind speed: {$weather['wind_speed']} m/s
    - Soil type: {$soil_type}

    For each pest or disease, provide:
    1. Name of the pest/disease
    2. Why it's a risk under these conditions
    3. Early warning signs to look for
    4. Prevention measures
    5. Treatment options if detected

    Format the response as JSON with the following structure:
    {
      \"pests\": [
        {
          \"name\": \"Pest/Disease Name\",
          \"risk_factors\": \"Why it's a risk\",
          \"warning_signs\": \"Early warning signs\",
          \"prevention\": \"Prevention measures\",
          \"treatment\": \"Treatment options\"
        }
      ]
    }";

    // Prepare request data
    $data = [
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => $prompt
                    ]
                ]
            ]
        ]
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        curl_close($ch);
        return [
            'status' => 'error',
            'message' => 'Failed to connect to Gemini API: ' . curl_error($ch)
        ];
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $result = json_decode($response, true);

    // Extract the text content from Gemini's response
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $text = $result['candidates'][0]['content']['parts'][0]['text'];

        // Try to extract JSON from the response
        preg_match('/\{.*\}/s', $text, $matches);

        if (!empty($matches)) {
            $json_str = $matches[0];
            $warnings = json_decode($json_str, true);

            if ($warnings && isset($warnings['pests'])) {
                return [
                    'status' => 'success',
                    'data' => $warnings['pests']
                ];
            }
        }

        // Fallback if JSON extraction fails
        return [
            'status' => 'error',
            'message' => 'Failed to parse Gemini response',
            'raw_response' => $text
        ];
    }

    return [
        'status' => 'error',
        'message' => 'Invalid response from Gemini API',
        'raw_response' => $response
    ];
}

// API endpoint to get recommendations
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    $action = sanitize($_GET['action']);

    switch ($action) {
        case 'crop_recommendations':
            if (isset($_GET['weather']) && isset($_GET['soil_type']) && isset($_GET['location'])) {
                $weather = json_decode($_GET['weather'], true);
                $soil_type = sanitize($_GET['soil_type']);
                $location = sanitize($_GET['location']);

                echo json_encode(getCropRecommendations($weather, $soil_type, $location));
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Missing required parameters'
                ]);
            }
            break;

        case 'pest_warnings':
            if (isset($_GET['weather']) && isset($_GET['crop_name']) && isset($_GET['soil_type'])) {
                $weather = json_decode($_GET['weather'], true);
                $crop_name = sanitize($_GET['crop_name']);
                $soil_type = sanitize($_GET['soil_type']);

                echo json_encode(getPestWarnings($weather, $crop_name, $soil_type));
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Missing required parameters'
                ]);
            }
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
