<?php declare(strict_types=1);

use App\Controllers\HealthCheckController;
use App\Controllers\WeatherController;
use App\Exceptions\HttpException;
use App\HttpErrorResponse;
use App\HttpResponse;
use App\Main;

require_once "vendor/autoload.php";

bootstrap();

function bootstrap()
{
    try {
        $main = Main::instance();

        initRoutes();

        $main->router->tryRoute();
    } catch (HttpException $e) {
        $response = HttpErrorResponse::createByException($e);

        $response->send();
    } catch (\Throwable $th) {
        $response = new HttpErrorResponse(
            "Something wrong",
            500
        );

        $response->send();

        error_log($th->getMessage());
        error_log($th->getTraceAsString());
    }
}

function initRoutes()
{
    $main = Main::instance();

    $main->router->addRoute(
        '/api',
        'GET',
        [WeatherController::class, 'response']
    );
    $main->router->addRoute(
        '/health-check',
        'GET',
        [HealthCheckController::class, 'response']
    );
}

