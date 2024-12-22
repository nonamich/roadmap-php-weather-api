<?php declare(strict_types=1);

namespace App;

use App\Exceptions\HttpException;

class HttpErrorResponse extends HttpResponse
{
    public static function createByException(HttpException $e)
    {
        return new self($e->getMessage(), $e->getCode());
    }

    public function __construct(string $message, int $statusCode)
    {
        parent::__construct(
            body: [
                'message' => $message,
                'statusCode' => $statusCode
            ],
            statusCode: $statusCode
        );
    }
}
