<?php declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\HttpException;
use App\HttpResponse;
use App\WeatherClientCache;

class WeatherController extends BaseController
{
    public static function response(): HttpResponse
    {
        $location = self::getQuery('location');

        if (empty($location)) {
            throw new HttpException('Location mist be set', 400);
        }

        $weather = (new WeatherClientCache($location))->request();

        return new HttpResponse($weather);
    }
}
