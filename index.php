<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', './php.log'); // Garanta que o servidor web pode escrever neste arquivo/pasta
error_reporting(E_ALL);

require_once __DIR__ . '/src/Infrastructure/Request.php';
require_once __DIR__ . '/src/Infrastructure/Response.php';
require_once __DIR__ . '/src/Handlers/BaseHandler.php';
require_once __DIR__ . '/src/Handlers/StudentsHandler.php';
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
        "For teacher/debug: " . $e->getMessage()
    );
    // exit; // sendError já tem exit
}

$handler = null;
$resource = $request->getResource();

switch ($resource) {
    case 'students':
        $handler = new StudentsHandler($request, $response);
        break;

    // Comente ou remova para o foco inicial ser apenas em 'students'
    // case 'classes':
    // case 'images':
    //     $handler = new NotImplementedHandler($request, $response);
    //     break;

    case '':
        $response->sendNotFound(
            'API Endpoint', // Mensagem mais genérica
            null,
            "No resource specified. Try '/api/students'."
        );
        // exit; // sendNotFound já tem exit
        break; // Adicionado para clareza, embora exit já saia

    default:
        // Se 'classes' ou 'images' forem chamadas e não estiverem no switch acima:
        if (in_array($resource, ['classes', 'images'])) {
            $handler = new NotImplementedHandler($request, $response);
        } else {
            $response->sendNotFound(
                "Resource '{$resource}'",
                null,
                "The requested resource is not available. Try '/api/students'."
            );
        }
        // exit; // sendNotFound já tem exit
        break; // Adicionado para clareza
}

if ($handler) {
    $handler->execute();
} else {
    // Este else só seria atingido se uma rota como 'classes' fosse comentada
    // e o default não a pegasse para NotImplementedHandler, o que não deve acontecer com a lógica atual.
    // Mas por segurança, se nenhum handler for definido e não houve exit:
    if (!headers_sent()) { // Verifica se a resposta já não foi enviada
        $response->sendError("Server error: No handler for resource '{$resource}'.", 500);
    }
}