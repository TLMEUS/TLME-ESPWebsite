<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/App/Controllers/Category.php class for the TLME-ESP Website
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
use App\Models\Category as CategoryModel;
use Core\Controller;
use Core\View;
use Exception;
use PDO;
use PDOException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Category Controller
 *
 * @extends Core\Controller
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Category extends Controller {

    /**
     * Index Action
     *
     * This action will display a list of the categories
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(): void {
        $categories = CategoryModel::getAll();
        View::render('Category/index.twig', ['categories' => $categories]);
    }

    /**
     * Add Action
     *
     * This action will display the add category page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addAction(): void {
        View::render('Category/add.twig');
    }

    /**
     * Add Data Action
     *
     * This action will add the category to the database
     *
     * @return void
     * @throws Exception
     */
    public function addDataAction(): void {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            throw new Exception("Method not allowed", 405);
        }
        $this->validateAddData($_POST);
        if(!CategoryModel::insert($_POST)) {
            throw new Exception("Unable to save category.", 500);
        }
        $this->indexAction();
    }

    /**
     * Update Action
     *
     * This action will display the update category page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws Exception
     */
    public function updateAction(): void {
        $old_cat = CategoryModel::getSingle($this->route_params['id']);
        if(!$old_cat) {
            throw new Exception("Unable to locate record", 404);
        }
        View::render('Category/update.twig', [
            "id" => $old_cat['id'],
            "category" => $old_cat['category'],
            "belowcost" => $old_cat['below_cost']
        ]);
    }

    /**
     * Update Category Action
     *
     * This action will update the category information in the database
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws Exception
     */
    public function updateDataAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            throw new Exception("Method not allowed", 405);
        }
        $this->validateUpdateData($_POST);
        if(!CategoryModel::update($_POST)) {
            throw new Exception("Unable to update category", 500);
        }
        $this->indexAction();
    }

    /**
     * Validate Add Data Method
     *
     * This method will validate the add category data
     *
     * @param array $data The category data to validate
     * @return void
     * @throws Exception
     */
    private function validateAddData(array $data): void {
        $category = $data["category"];
        if(!array_key_exists('below_cost', $data)) {
            throw new Exception("Missing below cost value.", 406);
        }
        $below_cost = $data["below_cost"];
        if(empty($category)) {
            throw new Exception("Category name can not be empty.", 406);
        }
        if(CategoryModel::checkName($category)) {
            throw new Exception("Category exists in database.", 406);
        }
    }

    /**
     * Validate Update Data Method
     *
     * This method will validate the update category data
     *
     * @param array $data The category data to validate
     * @return void
     * @throws Exception
     */
    private function validateUpdateData(array $data): void {
        if(!array_key_exists('id', $data)) {
            throw new Exception("Missing the category id.", 406);
        }
        $id = $data["id"];
        $category = $data["category"];
        if(!array_key_exists('below_cost', $data)) {
            throw new Exception("Missing below cost value.", 406);
        }
        $below_cost = $data["below_cost"];
        if(empty($category)) {
            throw new Exception("Category name can not be empty.", 406);
        }
    }
}