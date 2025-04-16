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

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['user_id']) || !is_numeric($data['user_id']) || !isset($data['is_admin'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input'
    ]);
    exit;
}

$user_id = intval($data['user_id']);
$is_admin = $data['is_admin'] ? 1 : 0;

// Don't allow admin to remove their own admin privileges
if ($user_id === $_SESSION['user_id'] && $is_admin === 0) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'You cannot remove your own admin privileges'
    ]);
    exit;
}

// Update user's admin status
$stmt = $conn->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
$stmt->bind_param("ii", $is_admin, $user_id);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Admin status updated successfully'
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update admin status: ' . $conn->error
    ]);
}

$stmt->close();
?>
