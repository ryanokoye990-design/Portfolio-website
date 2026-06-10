<?php
// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'portfolio_db');

/**
 * Get database connection
 * @return mysqli
 */
function getDBConnection() {
    static $connection = null;

    if ($connection === null) {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        // Check connection
        if ($connection->connect_error) {
            // Log error securely instead of exposing it
            error_log('Database connection failed: ' . $connection->connect_error);
            die(json_encode(['error' => 'Database connection failed. Please try again later.']));
        }

        // Set charset to utf8mb4
        $connection->set_charset('utf8mb4');
    }

    return $connection;
}

/**
 * Close database connection
 */
function closeDBConnection() {
    global $connection;
    if ($connection !== null) {
        $connection->close();
    }
}
?>
