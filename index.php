<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', './php.log');
error_reporting(E_ALL);

require_once __DIR__ . '/scr/Request.php';
require_once __DIR__ . '/scr/Response.php';

$request = new Request($_SERVER, $_GET, $_POST);

$response = new Response();

$resource = $request->getResource();

$method = $request->getMethod();

$RESOURCE_IS_COMPANIES = 'companies';
$RESOURCE_IS_PRODUCTS = 'products';
$RESOURCE_IS_IMAGES = 'images';

switch ($resource) {
    case $RESOURCE_IS_COMPANIES:
        $responseData = ['resource' => $RESOURCE_IS_COMPANIES];
        $response->sendSuccess($responseData);
        break;

    case $RESOURCE_IS_PRODUCTS:
        $responseData = ['resource' => $RESOURCE_IS_PRODUCTS];
        $response->sendSuccess($responseData);
        break;

    case $RESOURCE_IS_IMAGES:
        $responseData = ['resource' => $RESOURCE_IS_IMAGES];
        $response->sendSuccess($responseData);
        break;

    default:
        $response->sendError(
            "Recurso '$resource' não encontrado ou inválido.",
            404,
            "Recursos disponíveis: companies, products, images."
        );
        break;
}