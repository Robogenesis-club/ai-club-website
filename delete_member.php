<?php
include 'config.php'; // This now handles session_start() and both $conn and $conn_member
header('Content-Type: application/json');

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing ID']);
    exit();
}

$bennettid = $_POST['id'];

// Ensure $conn_member is available from config.php
if (!isset($conn_member) || !$conn_member) {
    error_log('robophp: $conn_member is null in delete_member.php');
    echo json_encode(['success' => false, 'message' => 'Database connection not available.']);
    exit();
}

$stmt = $conn_member->prepare("DELETE FROM personal WHERE bennettid = ?");
if (!$stmt) {
    error_log('robophp: delete_member prepare failed: ' . $conn_member->error);
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement.']);
    exit();
}

$stmt->bind_param("s", $bennettid);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    error_log('robophp: delete_member execute failed: ' . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to delete member.']);
}

$stmt->close();
// Connections are closed in secret_file.php or at the end of the request lifecycle
// if ($conn_member) $conn_member->close(); // Don't close here if other scripts might use it in the same request
?>
