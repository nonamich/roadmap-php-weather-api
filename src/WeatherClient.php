<?php declare(strict_types=1);

namespace App;

use App\Exceptions\HttpException;

class WeatherClient
{
    const API_URL = 'https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline';

    public function __construct(private readonly string $location)
    {
    }

    protected function getUrl()
    {
        $locationSafe = urlencode($this->location);
        $queryData = [
            'key' => $_ENV['WEATHER_API_KEY']
        ];

        return self::API_URL . "/$locationSafe?" . http_build_query($queryData);
    }

    public function request()
    {
        $url = $this->getUrl();
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($httpCode !== 200) {
            throw new HttpException("Got non 200 status code from 3rd party API", $httpCode);
        }

        return json_decode($output, true);
    }
}
