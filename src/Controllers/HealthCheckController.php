<?php declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\HttpException;
use App\HttpResponse;
use App\Main;
use App\WeatherClient;
use ErrorException;

class HealthCheckController extends BaseController
{

    public static function response(): HttpResponse
    {
        $randomString = bin2hex(random_bytes(5));
        $redis = Main::instance()->redis;
        $data = [];

        try {
            $redis->set($randomString, $randomString);
            $value = $redis->get($randomString);

            $redis->del($randomString);

            if ($value === $randomString) {
                $data['redis'] = 'ok';
            }
        } catch (\Throwable $th) {
            $data['redes'] = "not ok";
        }

        return new HttpResponse($data);
    }
}
