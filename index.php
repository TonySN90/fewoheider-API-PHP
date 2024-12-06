<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "autoload.php";

use app\routes\Router;

$router = new Router();

$router->addRoute('GET', '/', 'HomeController@index');
$router->addApiRoutes('/v1/bookings', 'BookingController');
$router->addApiRoutes('/v1/guests', 'GuestController');
$router->addApiRoutes('/v1/rooms', 'RoomController');

$router->dispatch();

