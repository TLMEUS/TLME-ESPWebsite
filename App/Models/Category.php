<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/App/Models/Category.php class for the TLME-ESP Website
 *
 * PHP Version: 8.2
 *
 * @author troylmarker
 * @version 1.0
 * @since 2023-3-23
 */
namespace App\Models;

/**
 * Import required classes
 */
use Core\Model;
use PDO;
use PDOException;

/**
 * Category Model
 *
 * This model provides access to the category table in the database
 *
 * @extends Core\Model
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Category extends Model {

    /**
     * getAll Method
     *
     * This method returns a list of all categories in an array
     *
     * @return array
     */
    public static function getAll(): array {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT * FROM category ORDER BY id');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    /**
     * getSingle method
     *
     * This method will return a single category
     *
     * @param string $id The id of the record to return
     * @return array|false
     */
    public static function getSingle(string $id): false|array {
        try {
            $db = static::getDB();
            $stmt = $db->prepare('SELECT * FROM category WHERE id = :id');
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }

    /**
     * checkName Method
     *
     * This method checks is a category is already in the database
     *
     * @param string $name The category name to check
     * @return bool true if category is in database, false otherwise
     */
    public static function checkName(string $name): bool {
        try {
            $db = static::getDB();
            $stmt = $db->prepare("SELECT * FROM category WHERE category = :name");
            $stmt->bindValue(":name", $name);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * insert Method
     *
     * This method inserts a new category into the database
     *
     * @param array $data Array contain the category data
     * @return bool true is inserted, false otherwise
     */
    public static function insert(array $data): bool {
        try {
            $category = $data['category'];
            if($data['below_cost'] == 'yes') {
                $below_cost = "1";
            } else {
                $below_cost = "0";
            }
            $db= static::getDB();
            $stmt = $db->prepare("INSERT INTO category (category, below_cost)  VALUES (:category, :below_cost)");
            $stmt->bindValue(":category", $category);
            $stmt->bindValue(":below_cost", $below_cost);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * update Method
     *
     * This method will update a record in the category table
     *
     * @param array $data Array containing the new category data
     * @return bool true if updated, false otherwise
     */
    public static function update(array $data): bool {
        $id = $data['id'];
        $category = $data['category'];
        if($data['below_cost'] == 'yes') {
            $below_cost = "1";
        } else {
            $below_cost = "0";
        }
        try {
            $db = static::getDB();
            $stmt = $db->prepare("UPDATE category SET category = :category, below_cost = :below_cost WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->bindValue(":category", $category);
            $stmt->bindValue(":below_cost", $below_cost);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }
}