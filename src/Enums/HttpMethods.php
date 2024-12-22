<?php declare(strict_types=1);

namespace App\Enums;

enum HttpMethods: string
{
    case GET = "GET";
    case POST = "POST";
    case DELETE = "DELETE";
    case PUT = "PUT";
    case HEAD = "HEAD";
    case PATCH = "PATCH";
}
