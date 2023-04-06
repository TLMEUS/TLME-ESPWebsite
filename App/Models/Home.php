<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/App/Models/Home.php class for the TLME-ESP Website
 *
 * PHP Version: 8.2
 *
 * @author troylmarker
 * @version 1.0
 * @since 2023-3-28
 */
namespace App\Models;

/**
 * Import needed classes
 */
use Core\Model;
use Exception;
use PDO;
use PDOException;

/**
 * Home Model class
 *
 * This model provides access to the user table in the database
 */
class Home extends Model {

    /**
     * saveAPIKey method
     *
     * This method saves user record to the database
     *
     * @param array $data
     * @return string|null
     */
    public static function saveAPIKey(array $data): ?string {
        $db = static::getDB();
        $sql = "INSERT INTO user (name, username, password_hash, api_key) VALUES (:name, :username, :password_hash, :api_key)";
        $stmt = $db->prepare($sql);
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        try {
            $api_key = bin2hex(random_bytes(16));
        } catch (Exception $e) {
        }
        try {
            $stmt->bindValue(":name", $_POST['name']);
            $stmt->bindValue(":username", $_POST['username']);
            $stmt->bindValue(":password_hash", $password_hash);
            $stmt->bindValue(":api_key", $api_key);
            $stmt->execute();
            $retval = $api_key;
        } catch (Exception $e) {
            $retval = false;
        }
        return $retval;
    }
}