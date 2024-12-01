<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "autoload.php";

use app\routes\Router;


$router = new Router();

//Home
$router->add('GET', '/', 'HomeController@index');

// Bookings
$router->add('GET', '/api/v1/bookings', 'BookingController@index');

// Guests
$router->add('GET', '/api/v1/guests', 'GuestsController@index');

// Rooms
$router->add('GET', '/api/v1/rooms', 'RoomsController@index');

/*$router->add('POST', '/api/v1/bookings', 'app\controllers\BookingController@store');*/

$router->dispatch();

