<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

// Include database connection
require_once '../../config/database.php';

// Get crop ID from request
if (!isset($_GET['crop_id']) || !is_numeric($_GET['crop_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid crop ID'
    ]);
    exit;
}

$crop_id = intval($_GET['crop_id']);

// Get crop data
$stmt = $conn->prepare("SELECT * FROM crops WHERE id = ?");
$stmt->bind_param("i", $crop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Crop not found'
    ]);
    exit;
}

$crop = $result->fetch_assoc();
$stmt->close();

// Return crop data
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'crop' => $crop
]);
?>
