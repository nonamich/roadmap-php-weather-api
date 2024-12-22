<?php declare(strict_types=1);

namespace App\Exceptions;

class HttpException extends \Exception
{
    public function __construct(string $message, int $statusCode = 500)
    {
        parent::__construct(
            message: $message,
            code: $statusCode
        );
    }
}
