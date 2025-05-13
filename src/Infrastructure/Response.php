<?php

class Response {
    private string $JSON_HEADER = 'Content-Type: application/json';

    public function sendSuccess(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        if ($statusCode !== 204) {
            header($this->JSON_HEADER);
            echo json_encode($data);
        }
        exit;
    }

    public function sendError(string $message, int $statusCode, ?string $details = null): void
    {
        http_response_code($statusCode);
        header($this->JSON_HEADER);

        $errorResponse = [
            'error' => [
                'status' => $statusCode,
                'message' => $message,
            ]
        ];

        if ($details !== null && is_string($details) && !empty($details))
        {
            $errorResponse['error']['details'] = $details;
        }

        echo json_encode($errorResponse);
        exit;
    }

    public function sendNotFound(string $resourceType = 'Resource', ?string $identifier = null, ?string $details = null): void
    {
        $message = ucfirst($resourceType);
        if ($identifier !== null) {
            $message .= " with ID '{$identifier}'";
        }
        $message .= " not found.";
        $this->sendError($message, 404, $details);
    }
}