<?php
/**
 * Konfiguracija baze podataka
 * InfinityFree MySQL konekcija
 */

define('DB_HOST', getenv('DB_HOST') ?: 'sqlXXX.infinityfree.com');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'if0_XXXXXXX_biblioteka');
define('DB_USER', getenv('DB_USER') ?: 'if0_XXXXXXX');
define('DB_PASS', getenv('DB_PASS') ?: 'CHANGE_ME');

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            error_log('Greška pri konekciji sa bazom: ' . $e->getMessage());
            die('Konekcija sa bazom trenutno nije dostupna. Proverite podešavanja u config/database.php.');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Sprečava kloniranje singleton objekta
    private function __clone() {}

    // Sprečava unserialize nad singleton objektom
    public function __wakeup() {
        throw new Exception("Singleton objekat ne može biti unserijalizovan");
    }
}

/**
 * Dohvatanje konekcije sa bazom
 */
function getDB() {
    return Database::getInstance()->getConnection();
}
