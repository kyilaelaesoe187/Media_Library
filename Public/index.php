<?php
session_start();

use Dotenv\Dotenv;

use inc\Database;

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/

use App\Repository\CatalogRepository;
use App\Repository\FormatRepository;
use App\Repository\UserRepository;

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

use App\Service\CatalogService;
use App\Service\FormatService;
use App\Service\UserService;
use App\Service\Validator;


/*
|--------------------------------------------------------------------------
| WEB CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Controller\CatalogController;
use App\Controller\DetailsController;
use App\Controller\SuggestController;
use App\Controller\AuthController;

/*
|--------------------------------------------------------------------------
| API CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Controller\Api\CatalogApiController;
use App\Controller\Api\DetailsApiController;
use App\Controller\Api\SuggestApiController;
use App\Controller\Api\AuthApiController;

/**
 * Front Controller
 */

error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('html_errors', 1);

define(
    'BASE_PATH',
    dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| AUTOLOAD
|--------------------------------------------------------------------------
*/

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/inc/Database.php';
require_once BASE_PATH . '/inc/CustomPath.php';

/*
|--------------------------------------------------------------------------
| ENVIRONMENT
|--------------------------------------------------------------------------
*/

$dotenv = Dotenv::createImmutable(
    dirname(__DIR__)
);

$dotenv->load();

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

$db = Database::getConnection();

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/

$catalogRepo = new CatalogRepository($db);

$formatRepo = new FormatRepository($db);

$userRepo = new UserRepository($db);

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

$catalogService = new CatalogService(
    $catalogRepo
);

$formatService = new FormatService(
    $formatRepo
);

$validator = new Validator();

$userService = new UserService(
    $userRepo,
    $validator
);

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

$catalogController = new CatalogController(
    $catalogService
);

$detailsController = new DetailsController(
    $catalogService
);

$suggestController = new SuggestController(
    $formatService
);

$authController = new AuthController(
    $userService
);

/*
|--------------------------------------------------------------------------
| API CONTROLLERS
|--------------------------------------------------------------------------
*/

$catalogApiController = new CatalogApiController(
    $catalogService
);

$detailsApiController = new DetailsApiController(
    $catalogService
);

$suggestApiController = new SuggestApiController(
    $formatService
);

$authApiController = new AuthApiController(
    $userService
);

/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/

$routes = [

    /*
    |--------------------------------------------------------------------------
    | WEB ROUTES
    |--------------------------------------------------------------------------
    */

    'home' => fn() =>
    $catalogController->home(),

    'catalog' => fn() =>
    $catalogController->index(),

    'details' => fn() =>
    $detailsController->show(),

    'suggest' => fn() =>
    $suggestController->index(),

    /*
    |--------------------------------------------------------------------------
    | AUTH ROUTES
    |--------------------------------------------------------------------------
    */

    'login' => fn() =>
    $authController->login(),

    'register' => fn() =>
    $authController->register(),

    'logout' => fn() =>
    $authController->logout(),

    /*
    |--------------------------------------------------------------------------
    | API ROUTES
    |--------------------------------------------------------------------------
    */

    'api-catalog' => fn() =>
    $catalogApiController->index(),

    'api-details' => fn() =>
    $detailsApiController->show(),

    'api-suggest' => fn() =>
    $suggestApiController->store(),

    /*
|--------------------------------------------------------------------------
| AUTH API ROUTES
|--------------------------------------------------------------------------
*/

    'api-login' => fn() =>
    $authApiController->login(),

    'api-register' => fn() =>
    $authApiController->register(),

    'api-logout' => fn() =>
    $authApiController->logout(),
];



/*
|--------------------------------------------------------------------------
| CURRENT PAGE
|--------------------------------------------------------------------------
*/

//$page = $_GET['page'] ?? 'home';
$page = $_GET['page'] ?? 'home';

/*
|--------------------------------------------------------------------------
| EXECUTE ROUTE
|--------------------------------------------------------------------------
*/

if (array_key_exists($page, $routes)) {

    $routes[$page]();
} else {

    $catalogController->home();
}
