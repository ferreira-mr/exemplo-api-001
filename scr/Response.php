<?php

class Response {
    private string $JSON_HEADER = 'Content-Type: application/json';
    public function sendSuccess(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header($this->JSON_HEADER);
        echo json_encode($data);
    }

    public function sendError(string $message, int $statusCode, string $details = null): void
    {
        http_response_code($statusCode);
        header($this->JSON_HEADER);

        $errorResponse = [
                'error' => [
                        'status' => $statusCode,
                        'message' => $message,
                ]
        ];

        if ($details !== null)
        {
            $errorResponse['error']['details'] = $details;
        }

        echo json_encode($errorResponse);
    }
}