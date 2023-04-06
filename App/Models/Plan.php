<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/App/Models/Plan.php class for the TLME-ESP Website
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

/**
 * Plan Model
 *
 * This model provides access to the plan table in the database
 *
 * @extends Core\Model
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Plan extends Model
{
    /**
     * getAllForCategory method
     *
     * This method will return an array containing the plan list from the database given the category
     *
     * @param string $parent The category id
     * @return array The list of plans
     */
    public static function getAllForCategory(string $parent): array {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM plan WHERE parent = :parent");
        $stmt->bindValue(":parent" , $parent);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * insertPlan Method
     *
     * This method will add a plan into the Plan database table
     *
     * @param string $parent The plan parent category
     * @param array $data The array containing the plan data
     * @return void
     */
    public static function insertPlan(string $parent, array $data) {
        $db = static::getDB();
        $sql = "INSERT INTO plan (id, parent, name, min, max, tier1term, tier1cost, tier1sku, tier2term, tier2cost, tier2sku) VALUES 
               (:id, :parent, :name, :min, :max, :tier1term, :tier1cost, :tier1sku, :tier2term, :tier2cost, :tier2sku)";
        $stmt = $db->prepare($sql);
        $new_id = static::getNextPlanId($parent);
        $stmt->bindValue(":id", $new_id);
        $stmt->bindValue(":parent", $parent);
        $stmt->bindValue(":name", $data['name']);
        $stmt->bindValue(":min", $data['min']);
        $stmt->bindValue(":max", $data['max']);
        $stmt->bindValue(":tier1term", $data['tier1term']);
        $stmt->bindValue(":tier1cost", $data['tier1cost']);
        $stmt->bindValue(":tier1sku", $data['tier1sku']);
        $stmt->bindValue(":tier2term", $data['tier2term']);
        $stmt->bindValue(":tier2cost", $data['tier2cost']);
        $stmt->bindValue(":tier2sku", $data['tier2sku']);
        $stmt->execute();
    }

    /**
     * deletePlan Method
     *
     * This method will delete a plan from the database
     *
     * @param string $parent The category Id
     * @param string $plan The plan Id
     * @return void
     */
    public static function deletePlan(string $parent, string $plan): void {
        $db = static::getDB();
        $stmt = $db->prepare("DELETE FROM plan WHERE parent = :parent AND id = :plan");
        $stmt->bindValue(":parent", $parent);
        $stmt->bindValue(":plan", $plan);
        $stmt->execute();
    }

    /**
     * getNextPlanId method
     *
     * Private method  to get the next plan id in a category
     *
     * @param string $parent The category id number
     * @return string The new plan id number
     */
    private static function getNextPlanId(string $parent):string {
        $db = static::getDB();
        $sql = "SELECT count(*) FROM plan WHERE parent = :parent";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":parent", $parent);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result + 1;
    }
}