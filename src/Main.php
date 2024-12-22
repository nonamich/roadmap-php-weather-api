<?php declare(strict_types=1);

namespace App;

use Predis\Client as PredisClient;
use Symfony\Component\Dotenv\Dotenv;
use App\RateLimit;
use App\Router;

final class Main
{
    public readonly PredisClient $redis;

    public static function instance()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new self();
        }

        return $instance;
    }

    private function __construct(public Router $router = new Router())
    {
        $this->initDotenv();
        $this->initRedis();
        $this->initRateLimit();
    }

    private function initRateLimit()
    {
        $rateLimit = new RateLimit($this->redis);

        $rateLimit->nextOrThrow();
    }

    private function initRedis()
    {
        $this->redis = new PredisClient($_ENV['REDIS_URL'] ?: "tcp://redis:6379");

        $this->redis->connect();
    }

    private function initDotenv()
    {
        $dotenv = new Dotenv();

        $dotenv->load(getcwd() . '/.env');
    }
}
