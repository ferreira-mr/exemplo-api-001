<?php

class Request {
    private string $methodValue;
    private string $uriValue;
    private string $resourceValue;
    private array $queryParameters;
    private array $requestPayload;
    private string $contentType;

    public function __construct(array $serverData, array $getData, array $postData)
    {
        $this->methodValue = $serverData['REQUEST_METHOD'] ?? '';
        $this->uriValue = $serverData['REQUEST_URI'] ?? '';
        $this->queryParameters = $getData; // $_GET
        $this->contentType = $serverData['CONTENT_TYPE'] ?? '';
        $this->requestPayload = [];

        $method = strtoupper($this->methodValue);

        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            if (stripos($this->contentType, 'application/json') !== false) {
                $input = file_get_contents('php://input');
                if ($input !== false) {
                    $jsonData = json_decode($input, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
                        $this->requestPayload = $jsonData;
                    }
                }
            } elseif (!empty($postData)) { // $_POST
                $this->requestPayload = $postData;
            }
        }
        $this->resourceValue = $this->setResource();
    }

    public function getMethod(): string
    {
        return strtolower($this->methodValue);
    }

    public function getUri(): string
    {
        return $this->uriValue;
    }

    private function setResource() : string
    {
        $path = parse_url($this->uriValue, PHP_URL_PATH);

        if ($path === false || $path === null) {
            return '';
        }

        $apiPrefix = '/api/';

        if (str_starts_with($path, $apiPrefix)) {
            $relevantPath = substr($path, strlen($apiPrefix));
            $segments = explode('/', trim($relevantPath, '/'));
            return $segments[0] ?? '';
        }

        return '';
    }

    public function getResource() : string
    {
        return $this->resourceValue;
    }

    public function getParams(string $param): ?string
    {
        return isset($this->queryParameters[$param]) && is_string($this->queryParameters[$param])
            ? $this->queryParameters[$param]
            : null;
    }

    public function getPostParam(string $param): mixed
    {
        return $this->requestPayload[$param] ?? null;
    }

    public function getAllQueryParams(): array
    {
        return $this->queryParameters;
    }

    public function getAllPostParams(): array
    {
        return $this->requestPayload;
    }
}