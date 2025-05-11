<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', './php.log');
error_reporting(E_ALL);

require_once __DIR__ . '/src/Request.php';
require_once __DIR__ . '/src/Response.php';
require_once __DIR__ . '/src/Handler.php';

$request = new Request($_SERVER, $_GET, $_POST);
$response = new Response();
$handler = null;

$resource = $request->getResource();

switch ($resource) {
    case 'companies':
        $handler = new Handler($request, $response);
        $handler->execute();
        break;
    case 'products':
        $handler = new Handler($request, $response);
        $handler->execute();
        break;
    case 'images':
        $handler = new Handler($request, $response);
        $handler->execute();
        break;

    default:
        $response->sendError(
            "Recurso '$resource' não encontrado ou inválido.",
            404,
            "Recursos disponíveis: companies, products, images."
        );
        exit;
}

if (session_status() == PHP_SESSION_ACTIVE) {
    session_write_close();
}