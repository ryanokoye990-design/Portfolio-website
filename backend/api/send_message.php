<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Database configuration
require_once __DIR__ . '/../config/db.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    http_response_code(405);
    exit;
}

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validate input
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Prevent spam - check message length
if (strlen($message) < 10 || strlen($message) > 5000) {
    echo json_encode(['success' => false, 'message' => 'Message must be between 10 and 5000 characters']);
    exit;
}

try {
    $db = getDBConnection();

    // Insert message into database
    $query = "INSERT INTO messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $db->prepare($query);

    if (!$stmt) {
        throw new Exception('Database error: ' . $db->error);
    }

    $stmt->bind_param('sss', $name, $email, $message);

    if ($stmt->execute()) {
        // Optional: Send email notification
        $to = 'your-email@example.com';
        $subject = 'New Portfolio Message from ' . $name;
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = 'From: ' . $email . "\r\n";
        // mail($to, $subject, $body, $headers);

        echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
    } else {
        throw new Exception('Failed to save message');
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    http_response_code(500);
}
?>