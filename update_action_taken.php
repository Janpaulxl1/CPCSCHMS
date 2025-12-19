<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

$id = $data['id'] ?? null;
$action_taken = $data['action_taken'] ?? '';

if (!$id || $conn === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid data or database connection failed']);
    exit;
}

$stmt = $conn->prepare("UPDATE notifications SET action_taken = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("si", $action_taken, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Action updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update action: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
