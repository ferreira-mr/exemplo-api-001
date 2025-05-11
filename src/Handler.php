<?php

class Handler
{
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(): void
    {
        $responseData = [
            'resource' => $this->request->getResource(),
            'method' => $this->request->getMethod(),
            'status' => 'success',
            'message' => 'GET executed'
        ];
        $this->response->sendSuccess($responseData);
    }

    public function post(): void
    {
        $responseData = [
            'resource' => $this->request->getResource(),
            'method' => $this->request->getMethod(),
            'status' => 'success',
            'message' => 'POST executed'
        ];
        $this->response->sendSuccess($responseData, 201);
    }

    public function put(): void
    {
        $responseData = [
            'resource' => $this->request->getResource(),
            'method' => $this->request->getMethod(),
            'status' => 'success',
            'message' => 'PUT executed'
        ];
        $this->response->sendSuccess($responseData);
    }

    public function delete(): void
    {
        $responseData = [
            'resource' => $this->request->getResource(),
            'method' => $this->request->getMethod(),
            'status' => 'success',
            'message' => 'DELETE executed'
        ];
        $this->response->sendSuccess($responseData);
    }

    public function execute(): void
    {
        $method = $this->request->getMethod();
        $this->$method();

    }
}