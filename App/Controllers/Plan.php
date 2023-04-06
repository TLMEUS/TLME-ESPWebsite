<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/App/Controllers/Plan.php class for the TLME-ESP Website
 *
 * PHP Version: 8.2
 *
 * @author troylmarker
 * @version 1.0
 * @since 2023-3-23
 */
namespace App\Controllers;

/**
 * Import required classes
 */
use Core\Controller;
use App\Models\Category as CategoryModel;
use App\Models\Plan as PlanModel;
use Core\View;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *  Plan Controller
 *
 * @extends Core\Controller
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Plan extends Controller
{

    /**
     * index Action
     *
     * This action display a drop-down list of categories to display the plans in that category
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(): void {
        $categories = CategoryModel::getAll();
        View::render('Plan/index.twig', ['categories' => $categories]);

    }

    /**
     * list Action
     *
     * This action display the plans in the selected category
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function listAction(): void {
        $category = CategoryModel::getSingle($_POST['id']);
        $plans = PlanModel::getAllForCategory($_POST['id']);
        View::render('Plan/list.twig', ['category' => $category, 'plans' => $plans]);
    }

    /**
     * add Action
     *
     * This action display the add plan form
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addAction(): void {
        $category = CategoryModel::getSingle($this->route_params['id']);
        View::render('Plan/add.twig', ['category' => $category]);
    }

    /**
     * addPlan Action
     *
     * This action will validate and write the plan to the database
     *
     * @return void
     * @throws Exception
     */
    public function addPlanAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            throw new Exception("Method not allowed", 405);
        }
        $this->validateAddData($_POST);
        PlanModel::insertPlan($_POST['parent'], $_POST);
        $this->indexAction();
    }


    /**
     * deletePlanAction method
     *
     * This method will delete a plan from the database
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function deletePlanAction(): void {
        $category = $this->route_params['grp'];
        $plan = $this->route_params['id'];
        PlanModel::deletePlan($category, $plan);
        $this->indexAction();
    }

    /**
     * validateAddData Action
     *
     * This private method will validate the add plan data
     *
     * @param array $data Array containing the data
     * @return void
     * @throws Exception
     */
    private function validateAddData(array $data): void {
        if (strlen($data['name']) > 100) {
            throw new Exception("Plan length is to long. Max of 100 characters.", 406);
        }
        if(empty($data['min'])) {
            throw new Exception("The minimum cost is not a valid value.", 406);
        }

        if(empty($data['max'])) {
            throw new Exception("The maximum cost is not a valid value.", 406);
        }
        if(empty($data['tier1term'])) {
            throw new Exception("Tier 1 term is required.", 406);
        }
        if (strlen($data['tier1term'] > 10)) {
            throw new Exception("Tier 1 Term is too long.", 406);
        }
        if (empty($data['tier1cost'])) {
            throw new Exception("Tier 1 cost is not a valid value.", 406);
        }
        if (empty($data['tier1sku'])) {
            throw new Exception("Tier 1 sku is nat a valid value.", 406);
        }
        if (!empty($data['tier2term'])) {
            if (strlen($data['tier2term'] > 10)) {
                throw new Exception("Tier 2 Term is too long.", 406);
            }
            if (empty($data['tier2cost'])) {
                throw new Exception("Tier 2 cost is not a valid value.", 406);
            }
            if (empty($data['tier2sku'])) {
                throw new Exception("Tier 2 sku is nat a valid value.", 406);
            }
        }
    }
}