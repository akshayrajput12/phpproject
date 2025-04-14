<?php
// Include database connection
require_once '../config/database.php';

// Gemini API key
define('GEMINI_API_KEY', 'AIzaSyBaop94QTnoPuBIgd_-swirOs2LlYgy8NI');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');

// Get the request data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is valid
if (!$data || !isset($data['message'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request data'
    ]);
    exit;
}

// Get message and language
$message = $data['message'];
$language = isset($data['language']) ? $data['language'] : 'en';

// Process the message
$response = getChatbotResponse($message, $language);

// Return the response
header('Content-Type: application/json');
echo json_encode($response);

/**
 * Get response from Gemini API
 * 
 * @param string $message User message
 * @param string $language Language code (en, hi, pa)
 * @return array Response data
 */
function getChatbotResponse($message, $language) {
    $api_key = GEMINI_API_KEY;
    
    // Prepare system prompt based on language
    $systemPrompt = getSystemPrompt($language);
    
    // API endpoint
    $url = GEMINI_API_URL . "?key={$api_key}";
    
    // Prepare request data
    $data = [
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => $systemPrompt . "\n\nUser: " . $message
                    ]
                ]
            ]
        ],
        "generationConfig" => [
            "temperature" => 0.7,
            "topK" => 40,
            "topP" => 0.95,
            "maxOutputTokens" => 1024,
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
        
        // Clean up the response
        $text = cleanResponse($text);
        
        return [
            'status' => 'success',
            'response' => $text
        ];
    }
    
    return [
        'status' => 'error',
        'message' => 'Invalid response from Gemini API',
        'raw_response' => $response
    ];
}

/**
 * Get system prompt based on language
 * 
 * @param string $language Language code
 * @return string System prompt
 */
function getSystemPrompt($language) {
    switch ($language) {
        case 'hi':
            return "आप एक कृषि सहायक हैं जो किसानों को फसल सुझाव, मौसम आधारित सावधानियों, कीट हमले की चेतावनियों और मौसम अलर्ट के साथ सहायता करता है। कृपया हिंदी में उत्तर दें। आपका नाम 'किसान मित्र' है।";
        case 'pa':
            return "ਤੁਸੀਂ ਇੱਕ ਖੇਤੀਬਾੜੀ ਸਹਾਇਕ ਹੋ ਜੋ ਕਿਸਾਨਾਂ ਨੂੰ ਫਸਲ ਸੁਝਾਅ, ਮੌਸਮ-ਅਧਾਰਿਤ ਸਾਵਧਾਨੀਆਂ, ਕੀੜੇ ਦੇ ਹਮਲੇ ਦੀਆਂ ਚੇਤਾਵਨੀਆਂ, ਅਤੇ ਮੌਸਮ ਅਲਰਟ ਨਾਲ ਸਹਾਇਤਾ ਕਰਦਾ ਹੈ। ਕਿਰਪਾ ਕਰਕੇ ਪੰਜਾਬੀ ਵਿੱਚ ਜਵਾਬ ਦਿਓ। ਤੁਹਾਡਾ ਨਾਮ 'ਕਿਸਾਨ ਮਿੱਤਰ' ਹੈ।";
        default:
            return "You are an agricultural assistant that helps farmers with crop suggestions, weather-based precautions, pest attack warnings, and weather alerts. Your name is 'Farmer's Friend'.";
    }
}

/**
 * Clean up the response text
 * 
 * @param string $text Response text
 * @return string Cleaned response
 */
function cleanResponse($text) {
    // Remove any "Assistant:" or similar prefixes
    $text = preg_replace('/^(Assistant|Farmer\'s Friend|किसान मित्र|ਕਿਸਾਨ ਮਿੱਤਰ):\s*/i', '', $text);
    
    // Remove any markdown formatting
    $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text); // Bold
    $text = preg_replace('/\*(.*?)\*/', '$1', $text);     // Italic
    
    return trim($text);
}
?>
