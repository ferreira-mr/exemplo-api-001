<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', './php.log');
error_reporting(E_ALL);

require_once __DIR__ . '/src/Request.php';
require_once __DIR__ . '/src/Response.php';
require_once __DIR__ . '/src/BaseHandler.php';
require_once __DIR__ . '/src/StudentsHandler.php';
require_once __DIR__ . '/src/NotImplementedHandler.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/BaseModel.php';
require_once __DIR__ . '/src/StudentModel.php';

$request = new Request($_SERVER, $_GET, $_POST);
$response = new Response();

$pdo = null;
try {
    $pdo = Database::getConnection();
    BaseModel::setConnection($pdo);
} catch (\PDOException $e) {
    $response->sendError(
        "Database connection failed: " . $e->getMessage(),
        500
    );
    exit;
}

$handler = null;

$resource = $request->getResource();

switch ($resource) {
    case 'students':
        $handler = new StudentsHandler($request, $response);
        $handler->execute();
        break;

    case 'classes':

    case 'images':
        $handler = new NotImplementedHandler($request, $response);
        $handler->execute();
        break;

    default:
        $response->sendError(
            "Resource '$resource' not found or invalid.",
            404,
            "Available resources: students, classes, images."
        );
        exit;
}