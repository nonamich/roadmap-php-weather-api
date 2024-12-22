<?php declare(strict_types=1);

namespace App;

use App\Exceptions\HttpException;
use App\WeatherClient;

class WeatherClientCache extends WeatherClient
{
    const TTL = 60 * 60 * 12;

    private function getCacheKey()
    {
        $url = urlencode($this->getUrl());

        return "cache:$url";
    }

    public function request()
    {
        $redis = Main::instance()->redis;
        $cacheKey = $this->getCacheKey();
        $cache = $redis->get($cacheKey);

        if ($cache) {
            return unserialize($cache);
        }

        $currentData = parent::request();

        $redis->set(
            $cacheKey,
            serialize($currentData),
            'EX',
            self::TTL
        );

        return $currentData;
    }
}
