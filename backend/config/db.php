<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'portfolio_db');

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
            die('Connection failed: ' . $connection->connect_error);
        }

        // Set charset to utf8mb4
        $connection->set_charset('utf8mb4');
    }

    return $connection;
}
?>