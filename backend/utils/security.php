<?php
/**
 * Security utility functions
 */

/**
 * Sanitize string input to prevent XSS
 * @param string $input
 * @return string
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 * @param string $email
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate message length
 * @param string $message
 * @param int $minLength
 * @param int $maxLength
 * @return bool
 */
function validateMessageLength($message, $minLength = 10, $maxLength = 5000) {
    $length = strlen($message);
    return $length >= $minLength && $length <= $maxLength;
}

/**
 * Get client IP address
 * @return string
 */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Log API requests for debugging and monitoring
 * @param string $endpoint
 * @param string $method
 * @param array $data
 * @param int $statusCode
 */
function logRequest($endpoint, $method, $data, $statusCode) {
    $logFile = __DIR__ . '/../logs/api.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logEntry = json_encode([
        'timestamp' => date('Y-m-d H:i:s'),
        'endpoint' => $endpoint,
        'method' => $method,
        'ip' => getClientIP(),
        'status' => $statusCode,
        'data' => $data
    ]);
    
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
}

/**
 * Send JSON response with appropriate headers
 * @param array $data
 * @param int $statusCode
 */
function sendJSON($data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}
?>