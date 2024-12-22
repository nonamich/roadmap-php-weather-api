<?php declare(strict_types=1);

namespace App;

class HttpResponse
{
    public function __construct(
        public readonly string|array $body,
        public readonly ?string $contentType = null,
        public readonly int $statusCode = 200
    ) {
    }

    public function send()
    {
        $contentType = $this->contentType ?: 'text/plain';
        $response = "";

        if (is_array($this->body)) {
            $contentType = 'application/json';

            $response = json_encode($this->body, JSON_PRETTY_PRINT);
        } else {
            $response = $this->body;
        }

        http_response_code($this->statusCode);
        header("Content-Type: $contentType");

        echo $response;

    }
}
