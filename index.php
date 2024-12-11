<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "autoload.php";
require_once __DIR__ . '/vendor/autoload.php';

use app\routes\Router;
use app\middleware\JwtAuthMiddleware;
use Dotenv\Dotenv;
use app\middleware\CorsMiddleware;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();

$jwtAuthMiddleware = new JwtAuthMiddleware();
$corsMiddleware = new CorsMiddleware();
$corsMiddleware->handle();

// Index
$router->addRoute('GET', '/', 'HomeController@index');

// Auth
$router->addRoute('POST', '/auth/register', 'AuthController@register');
$router->addRoute('POST', '/auth/login', 'AuthController@login');
$router->addRoute('GET', '/auth/verify', 'AuthController@verifyAccount');

// API
$router->addApiRoutes('/v1/bookings', 'BookingController', [$jwtAuthMiddleware, 'handle']);
$router->addApiRoutes('/v1/guests', 'GuestController', [$jwtAuthMiddleware, 'handle']);
$router->addApiRoutes('/v1/rooms', 'RoomController', [$jwtAuthMiddleware, 'handle']);

$router->dispatch();

