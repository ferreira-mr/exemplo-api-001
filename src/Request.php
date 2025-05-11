<?php

class Request {
    private string $methodValue;
    private string $uriValue;
    private string $resourceValue;
    private array $queryParameters;
    private array $postParameters;

    public function __construct(array $serverData, array $getData, array $postData)
    {
        $this->methodValue = $serverData['REQUEST_METHOD'] ?? '';
        $this->uriValue = $serverData['REQUEST_URI'] ?? '';
        $this->queryParameters = $getData;
        $this->postParameters = $postData;

        $this->resourceValue = $this->setResource();
    }

    public function getMethod(): string
    {
        return strtolower($this->methodValue);
    }

    public function getUri(): string
    {
        return  $this->uriValue;
    }

    private function setResource() : string
    {
        $path = parse_url($this->uriValue, PHP_URL_PATH);

        if ($path === false || $path === null) {
            return '';
        }

        $apiPrefix = '/api/';
        $apiPrefixLength = strlen($apiPrefix);

        if (str_starts_with($path, $apiPrefix)) {
            $resourcePath = substr($path, $apiPrefixLength);
            return trim($resourcePath, '/');
        }

        return '';
    }

    public function getResource() : string
    {
        return $this->resourceValue;
    }

    public function getParams(string $param) : string
    {
        return $this->queryParameters[$param];
    }

    public function getPostParam(string $param) : string
    {
        return $this->postParameters[$param];
    }

    public function getAllQueryParams(): array
    {
        return $this->queryParameters;
    }

    public function getAllPostParams(): array
    {
        return $this->postParameters;
    }
}
