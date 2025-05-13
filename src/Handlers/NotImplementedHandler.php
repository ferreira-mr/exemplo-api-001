<?php

require_once __DIR__ . '/../Infrastructure/Request.php';
require_once __DIR__ . '/../Infrastructure/Response.php';

class NotImplementedHandler
{
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function execute(): void
    {
        $method = strtoupper($this->request->getMethod());

        $this->response->sendError(
            "Resource method {$method} not implemented for '" . $this->request->getResource() . "'.",
            501
        );
    }
}