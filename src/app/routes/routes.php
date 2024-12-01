<?php

use app\controller\BookingController;
use app\controller\GuestsController;
use app\controller\RoomsController;
use app\connections\Database;

$db = (new Database())->connect();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/':
        echo json_encode(['status' => 'success', 'message' => 'API ready ;)']);
        break;
    case '/api/v1/bookings:id':
        $controller = new BookingController($db);
        $controller->index();
        break;
    case '/api/v1/rooms':
        $controller = new RoomsController($db);
        $controller->index();
        break;
    case '/api/v1/guests':
        $controller = new GuestsController($db);
        $controller->index();
        break;
    default:
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Route Not found']);
        break;
}