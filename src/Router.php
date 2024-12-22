<?php declare(strict_types=1);

namespace App;

use App\Enums\HttpMethods;
use App\Exceptions\HttpException;

class Router
{

    /**
     * @var array<string, array<value-of<HttpMethods>, callable(): HttpResponse>>
     */
    private array $routing;

    /**
     * @param value-of<HttpMethods> $method
     * @param callable(): HttpResponse $callback
     */
    public function addRoute(string $pathname, string $method, callable $callback)
    {
        $this->routing[$pathname][$method] = $callback;
    }

    public function getStrictPathname(string $pathname)
    {
        return preg_replace(
            '/(.)(\/$)/',
            '$1',
            $pathname
        );
    }

    public function tryRoute()
    {
        $pathname = $this->getStrictPathname(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );

        $method = $_SERVER['REQUEST_METHOD'];

        if (
            !isset($this->routing[$pathname][$method]) ||
            !is_callable($this->routing[$pathname][$method])
        ) {
            $response = new HttpErrorResponse("Nothing found", 404);

            $response->send();

            return;
        }

        $callback = $this->routing[$pathname][$method];
        $response = $callback();

        $response->send();
    }
}
