<?php declare(strict_types=1);
session_start();

use App\Controllers\ProfileController;
use App\Collections\UserCollection;
use Twig\Extra\CssInliner\CssInlinerExtension;
use League\Container\Container;
use App\Middlewares\AuthMiddleware;
use App\Services\HomeService;
use App\Controllers\HomeController;
use App\Controllers\FileController;
use App\Middlewares\InputValidationMiddleware;
use App\Models\Profile;
use App\Repositories\Database;
use App\Repositories\ProfileRepository;
use App\Services\FileService;
use App\Repositories\FileRepository;
use App\Services\ProfileService;
use App\Services\PopulateProfileService;

require 'vendor/autoload.php';

$loader = new Twig\Loader\FilesystemLoader('app/Views');
$twig = new Twig\Environment($loader,
    [
        'debug' => true
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    $twig->addExtension(new CssInlinerExtension());

$container = new League\Container\Container;
$container->add(Database::class);
$container->add(HomeService::class);
$container->add(View::class);
$container->add(Profile::class);
$container->add(UserCollection::class);

$container->add(ProfileRepository::class)->addArgument(Database::class);
$container->add(ProfileService::class)->addArgument(ProfileRepository::class);
$container->add(PopulateProfileService::class)->addArgument(ProfileRepository::class);
$container->add(ProfileController::class)->addArgument(ProfileService::class);

$container->add(FileRepository::class)->addArgument(Database::class);
$container->add(FileService::class)->addArgument(FileRepository::class);
$container->add(FileController::class)->addArgument(FileService::class);

$container->add(HomeController::class)->addArgument(HomeService::class);

// FASTROUTE
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [ProfileController::class, 'home']);
    $r->addRoute('GET', '/enter', [HomeController::class, 'home']);
    $r->addRoute('POST', '/login', [ProfileController::class, 'login']);
    $r->addRoute('POST', '/register', [ProfileController::class, 'register']);
    $r->addRoute('POST', '/upload', [FileController::class, 'save']);
    $r->addRoute('POST', '/rate', [ProfileController::class, 'rateProfile']);
});

// MIDDLEWARES

$middlewares = [
    ProfileController::class . '@home' => AuthMiddleware::class,
    ProfileController::class . '@register' => InputValidationMiddleware::class,
    ProfileController::class . '@login' => InputValidationMiddleware::class

];

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];
        $class = $routeInfo[1][0];

        $middlewareKey = $class . '@' . $method;

        if (key_exists($middlewareKey, $middlewares)) {
            $controllerMiddleware = $middlewares[$middlewareKey];
            $middlewareObject = new $controllerMiddleware;
            $middlewareObject->handle();
        }

        $action = $container->get($class)->$method();
        echo $twig->render($action[0], 
        ['post' => [$action[1]]]);
        break;
    }
?>