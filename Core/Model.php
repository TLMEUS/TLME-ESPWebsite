<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/Core/Model.php class for the TLME-Framework
 *
 * PHP Version: 8.2
 *
 * @author troylmarker
 * @version 1.0
 * @since 2023-3-23
 */
namespace Core;

/**
 *  Import needed classes
 */
use App\Config;
use PDO;
use PDOException;

/**
 *  Core Model class definition
 */
abstract class Model {

    /**
     * Get the PDO database connection
     *
     * @return PDO|null
     */
    protected static function getDB(): ?PDO
    {
        static $db = null;
        if ($db === null) {
            try {
                $dsn = "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8";
                $db = new PDO($dsn, Config::DB_USERNAME, Config::DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        return $db;
    }
}