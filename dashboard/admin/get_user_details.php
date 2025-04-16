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

// Get user ID from request
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid user ID'
    ]);
    exit;
}

$user_id = intval($_GET['user_id']);

// Get user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'User not found'
    ]);
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Get user's crops
$stmt = $conn->prepare("SELECT * FROM crops WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$crops = [];

while ($row = $result->fetch_assoc()) {
    $crops[] = $row;
}

$stmt->close();

// Return user data and crops
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'user' => $user,
    'crops' => $crops
]);
?>
