<?php
/**
 * Database Configuration and Connection
 * For XAMPP MySQL setup
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'pcb_site');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP MySQL password (empty by default)

/**
 * Get database connection using PDO
 * @return PDO|null Returns PDO connection or null on failure
 */
function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        return null;
    }
}
?>
