<?php

/**
 * Main application entry point.
 * Front Controller (All requests come here)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/inc/Database.php';
require_once BASE_PATH . '/inc/CustomPath.php';

use Dotenv\Dotenv;

/*
|--------------------------------------------------------------------------
| ENVIRONMENT
|--------------------------------------------------------------------------
*/

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/*
|--------------------------------------------------------------------------
| SHARED DEPENDENCIES
|--------------------------------------------------------------------------
*/
$db = Database::getConnection();

/* Repositories */
$catalogRepo = new CatalogRepository($db);
$formatRepo  = new FormatRepository($db);

/* Services */
$catalogService = new CatalogService($catalogRepo);
$formatService  = new FormatService($formatRepo);

/*
|--------------------------------------------------------------------------
| ROUTING
|--------------------------------------------------------------------------
*/
$page = $_GET['page'] ?? 'home';

switch ($page) {

    /*
    |--------------------------------------------------------------------------
    | WEB ROUTES (HTML VIEWS)
    |--------------------------------------------------------------------------
    */

    case 'home':
        $controller = new CatalogController($catalogService);
        $controller->home();
        break;

    case 'catalog':
        $controller = new CatalogController($catalogService);
        $controller->index();
        break;

    case 'details':
        $controller = new DetailsController($catalogService);
        $controller->show();
        break;

    case 'suggest':
        $controller = new SuggestController($formatService);
        $controller->index();
        break;


    /*
    |--------------------------------------------------------------------------
    | API ROUTES (POSTMAN TESTING - JSON RESPONSE)
    |--------------------------------------------------------------------------
    */

    case 'api-catalog':
        require_once BASE_PATH . '/Controller/Api/CatalogApiController.php';
        $controller = new CatalogApiController($catalogService);
        $controller->index();
        break;

    case 'api-details':
        require_once BASE_PATH . '/Controller/Api/DetailsApiController.php';
        $controller = new DetailsApiController($catalogService);
        $controller->show();
        break;

    case 'api-suggest':
        require_once BASE_PATH . '/Controller/Api/SuggestApiController.php';
        $controller = new SuggestApiController($formatService);
        $controller->store();
        break;


    /*
    |--------------------------------------------------------------------------
    | DEFAULT ROUTE
    |--------------------------------------------------------------------------
    */

    default:
        $controller = new CatalogController($catalogService);
        $controller->home();
        break;
}
