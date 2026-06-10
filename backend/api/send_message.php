<?php
// Set response headers
header('Content-Type: application/json');

// Import security and database functions
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../utils/security.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSON(['error' => 'Invalid request method. Only POST requests are allowed.'], 405);
}

try {
    // Get form data from JSON or POST
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    
    if (strpos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $message = $input['message'] ?? '';
    } else {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';
    }

    // Sanitize inputs
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);
    $message = sanitizeInput($message);

    // Validate inputs
    if (empty($name) || empty($email) || empty($message)) {
        logRequest('/api/send_message', 'POST', ['error' => 'Missing required fields'], 400);
        sendJSON(['error' => 'All fields are required'], 400);
    }

    // Validate email
    if (!validateEmail($email)) {
        logRequest('/api/send_message', 'POST', ['error' => 'Invalid email'], 400);
        sendJSON(['error' => 'Invalid email address'], 400);
    }

    // Validate message length
    if (!validateMessageLength($message, 10, 5000)) {
        logRequest('/api/send_message', 'POST', ['error' => 'Invalid message length'], 400);
        sendJSON(['error' => 'Message must be between 10 and 5000 characters'], 400);
    }

    $db = getDBConnection();

    // Insert message into database using prepared statement
    $query = "INSERT INTO messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $db->prepare($query);

    if (!$stmt) {
        error_log('Failed to prepare statement: ' . $db->error);
        logRequest('/api/send_message', 'POST', [], 500);
        sendJSON(['error' => 'Database error occurred'], 500);
    }

    $stmt->bind_param('sss', $name, $email, $message);

    if ($stmt->execute()) {
        // Optional: Send email notification
        $adminEmail = getenv('ADMIN_EMAIL') ?: 'your-email@example.com';
        
        if ($adminEmail !== 'your-email@example.com') {
            $subject = 'New Portfolio Message from ' . $name;
            $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
            $headers = 'From: ' . $email . "\r\n" .
                      'Reply-To: ' . $email . "\r\n" .
                      'Content-Type: text/plain; charset=UTF-8' . "\r\n";
            
            // Use mail() with error handling
            if (!mail($adminEmail, $subject, $body, $headers)) {
                error_log('Failed to send email notification for message from ' . $email);
            }
        }

        logRequest('/api/send_message', 'POST', ['status' => 'success'], 200);
        sendJSON(['success' => true, 'message' => 'Message sent successfully'], 200);
    } else {
        error_log('Failed to execute statement: ' . $stmt->error);
        logRequest('/api/send_message', 'POST', [], 500);
        sendJSON(['error' => 'Failed to save message. Please try again.'], 500);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log('Exception in send_message: ' . $e->getMessage());
    logRequest('/api/send_message', 'POST', ['error' => $e->getMessage()], 500);
    sendJSON(['error' => 'An error occurred while processing your message'], 500);
}
?>