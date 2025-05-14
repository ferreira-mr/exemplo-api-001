<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/Infrastructure/Request.php';
require_once __DIR__ . '/src/Infrastructure/Response.php';
require_once __DIR__ . '/src/Handlers/BaseHandler.php';
require_once __DIR__ . '/src/Handlers/StudentsHandler.php';
require_once __DIR__ . '/src/Handlers/ClassroomHandler.php';
require_once __DIR__ . '/src/Handlers/NotImplementedHandler.php';
require_once __DIR__ . '/src/Infrastructure/Database.php';
require_once __DIR__ . '/src/Models/BaseModel.php';
require_once __DIR__ . '/src/Models/StudentModel.php';

$request = new Request($_SERVER, $_GET, $_POST);
$response = new Response();

try {
    $pdo = Database::getConnection();
    BaseModel::setConnection($pdo);
} catch (\PDOException $e) {
    $response->sendError(
        "Database connection failed. Check configuration or if service is running.",
        500,
        "Error: " . $e->getMessage()
    );
}

$handler = null;
$resource = $request->getResource();

switch ($resource) {
    case 'students':
        $handler = new StudentsHandler($request, $response);
        break;
    case 'classrooms':
        $handler = new ClassroomHandler($request, $response);
        break;

    case '':
        $response->sendNotFound(
            'API Endpoint',
            null,
            "No resource specified. Try '/api/students'."
        );
        break;

    default:
        if (in_array($resource, ['classes', 'images'])) {
            $handler = new NotImplementedHandler($request, $response);
        } else {
            $response->sendNotFound(
                "Resource '{$resource}'",
                null,
                "The requested resource is not available. Try '/api/students'."
            );
        }
        break;
}

if ($handler) {
    $handler->execute();
} else {
    if (!headers_sent()) {
        $response->sendError("Server error: No handler for resource '{$resource}'.", 500);
    }
}