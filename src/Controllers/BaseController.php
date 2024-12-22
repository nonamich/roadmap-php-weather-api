<?php declare(strict_types=1);

namespace App\Controllers;

use App\HttpResponse;
use App\Interfaces\ControllerInterface;

abstract class BaseController implements ControllerInterface
{
    public static abstract function response(): HttpResponse;

    protected static function getQuery(string $name)
    {
        static $query = null;

        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str(
                $_SERVER['QUERY_STRING'],
                $query
            );
        }

        if (isset($query[$name])) {
            return (string) $query[$name];
        }

        return null;
    }
}
