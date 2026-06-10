<?php
// Set response headers
header('Content-Type: application/json');

// Import security and database functions
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../utils/security.php';

try {
    // Get database connection
    $db = getDBConnection();

    // Fetch all published projects from database using prepared statement
    $query = "SELECT id, title, description, image, link FROM projects WHERE published = 1 ORDER BY created_at DESC";
    $result = $db->query($query);

    if (!$result) {
        error_log('Database query failed: ' . $db->error);
        logRequest('/api/get_projects', 'GET', [], 500);
        sendJSON(['error' => 'Failed to fetch projects'], 500);
    }

    $projects = [];
    while ($row = $result->fetch_assoc()) {
        // Sanitize output to prevent XSS
        $projects[] = [
            'id' => (int) $row['id'],
            'title' => sanitizeInput($row['title']),
            'description' => sanitizeInput($row['description']),
            'image' => sanitizeInput($row['image']),
            'link' => sanitizeInput($row['link'])
        ];
    }

    logRequest('/api/get_projects', 'GET', ['count' => count($projects)], 200);
    sendJSON($projects, 200);

} catch (Exception $e) {
    error_log('Exception in get_projects: ' . $e->getMessage());
    logRequest('/api/get_projects', 'GET', ['error' => $e->getMessage()], 500);
    sendJSON(['error' => 'An error occurred while fetching projects'], 500);
}
?>