<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/App/Controllers/Home.php class for the TLME-Tube Website Project
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
use App\Models\Home as HomeModel;
use Core\Controller;
use Core\View;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Home Controller
 *
 * @extends Core\Controller
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */

class Home extends Controller {

    /**
     * Show the index page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(): void
    {
        View::render('Home/index.twig');
    }

    /**
     * apikeyAction method
     *
     * This method will display the apikey registration page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function apikeyAction(): void {
        View::render('Home/apikey.twig');
    }

    /**
     * saveAPIKeyAction method
     *
     * This method will save the api key and present it to the user
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function saveAPIKeyAction(): void {
        if($_SERVER["REQUEST_METHOD"] !== "POST") {
            throw new Exception("Request method not allowed.", 405);
        }
        $result = HomeModel::saveAPIKey($_POST);
        if (!$result) {
            View::render('Home/noapikey.twig');
        } else {
            View::render('Home/keycreated.twig', ['apikey' => $result]);
        }
    }
}