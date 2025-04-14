<?php
// Include database connection
require_once '../config/database.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'User not authenticated'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

/**
 * Get all crops for a user
 * 
 * @param int $user_id User ID
 * @return array Crops data
 */
function getUserCrops($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM crops WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $crops = [];
    while ($row = $result->fetch_assoc()) {
        $crops[] = $row;
    }
    
    $stmt->close();
    
    return [
        'status' => 'success',
        'data' => $crops
    ];
}

/**
 * Add a new crop for a user
 * 
 * @param int $user_id User ID
 * @param string $crop_name Crop name
 * @param string $planting_date Planting date
 * @param string $notes Notes
 * @return array Status
 */
function addCrop($user_id, $crop_name, $planting_date, $notes) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO crops (user_id, crop_name, planting_date, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $crop_name, $planting_date, $notes);
    
    if ($stmt->execute()) {
        $crop_id = $stmt->insert_id;
        $stmt->close();
        
        return [
            'status' => 'success',
            'message' => 'Crop added successfully',
            'crop_id' => $crop_id
        ];
    } else {
        $error = $stmt->error;
        $stmt->close();
        
        return [
            'status' => 'error',
            'message' => 'Failed to add crop: ' . $error
        ];
    }
}

/**
 * Update a crop
 * 
 * @param int $crop_id Crop ID
 * @param int $user_id User ID
 * @param string $crop_name Crop name
 * @param string $planting_date Planting date
 * @param string $notes Notes
 * @return array Status
 */
function updateCrop($crop_id, $user_id, $crop_name, $planting_date, $notes) {
    global $conn;
    
    // Check if crop belongs to user
    $stmt = $conn->prepare("SELECT id FROM crops WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $crop_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        return [
            'status' => 'error',
            'message' => 'Crop not found or not authorized'
        ];
    }
    
    $stmt->close();
    
    // Update crop
    $stmt = $conn->prepare("UPDATE crops SET crop_name = ?, planting_date = ?, notes = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssii", $crop_name, $planting_date, $notes, $crop_id, $user_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return [
            'status' => 'success',
            'message' => 'Crop updated successfully'
        ];
    } else {
        $error = $stmt->error;
        $stmt->close();
        
        return [
            'status' => 'error',
            'message' => 'Failed to update crop: ' . $error
        ];
    }
}

/**
 * Delete a crop
 * 
 * @param int $crop_id Crop ID
 * @param int $user_id User ID
 * @return array Status
 */
function deleteCrop($crop_id, $user_id) {
    global $conn;
    
    // Check if crop belongs to user
    $stmt = $conn->prepare("SELECT id FROM crops WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $crop_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        return [
            'status' => 'error',
            'message' => 'Crop not found or not authorized'
        ];
    }
    
    $stmt->close();
    
    // Delete crop
    $stmt = $conn->prepare("DELETE FROM crops WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $crop_id, $user_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return [
            'status' => 'success',
            'message' => 'Crop deleted successfully'
        ];
    } else {
        $error = $stmt->error;
        $stmt->close();
        
        return [
            'status' => 'error',
            'message' => 'Failed to delete crop: ' . $error
        ];
    }
}

// API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    $action = sanitize($_GET['action']);
    
    switch ($action) {
        case 'get_crops':
            echo json_encode(getUserCrops($user_id));
            break;
            
        default:
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid action'
            ]);
    }
    
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request data'
        ]);
        exit;
    }
    
    $action = $data['action'] ?? '';
    
    switch ($action) {
        case 'add_crop':
            if (isset($data['crop_name'])) {
                $crop_name = sanitize($data['crop_name']);
                $planting_date = sanitize($data['planting_date'] ?? date('Y-m-d'));
                $notes = sanitize($data['notes'] ?? '');
                
                echo json_encode(addCrop($user_id, $crop_name, $planting_date, $notes));
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Missing required parameters'
                ]);
            }
            break;
            
        case 'update_crop':
            if (isset($data['crop_id']) && isset($data['crop_name'])) {
                $crop_id = (int) $data['crop_id'];
                $crop_name = sanitize($data['crop_name']);
                $planting_date = sanitize($data['planting_date'] ?? date('Y-m-d'));
                $notes = sanitize($data['notes'] ?? '');
                
                echo json_encode(updateCrop($crop_id, $user_id, $crop_name, $planting_date, $notes));
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Missing required parameters'
                ]);
            }
            break;
            
        case 'delete_crop':
            if (isset($data['crop_id'])) {
                $crop_id = (int) $data['crop_id'];
                
                echo json_encode(deleteCrop($crop_id, $user_id));
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
