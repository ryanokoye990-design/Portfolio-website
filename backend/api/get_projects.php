<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database configuration
require_once __DIR__ . '/../config/db.php';

try {
    // Get database connection
    $db = getDBConnection();

    // Fetch all projects from database
    $query = "SELECT id, title, description, image, link FROM projects WHERE published = 1 ORDER BY created_at DESC";
    $result = $db->query($query);

    $projects = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }

    echo json_encode($projects);
} catch (Exception $e) {
    echo json_encode([
        'error' => 'Failed to fetch projects',
        'message' => $e->getMessage()
    ]);
    http_response_code(500);
}
?>