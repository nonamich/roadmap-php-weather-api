<?php declare(strict_types=1);

namespace App;

use App\Exceptions\HttpException;
use Predis\Client as PredisClient;

class RateLimit
{
    const MAX_REQUESTS = 100;
    private string $uniqueKey;
    private string $redisKey;
    private int $windowTime = 60 * 15;

    public function __construct(private PredisClient $redis)
    {
        $this->uniqueKey = $this->generateUniqueKey();
        $this->redisKey = $this->generateRedisKey();

        $this->prepareCount();
        $this->incrementCount();
    }

    private function incrementCount()
    {
        $this->redis->incr($this->redisKey);
    }

    private function getCount()
    {
        return (int) $this->redis->get($this->redisKey) ?: 0;
    }

    private function prepareCount()
    {
        $rate = $this->getCount();

        if (!$rate) {
            $this->redis->set(
                $this->redisKey,
                0,
                'ex',
                $this->windowTime
            );
        }
    }

    private function generateRedisKey()
    {
        return "rate-limit:{$this->uniqueKey}";
    }

    private function generateUniqueKey()
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

        return $ip;
    }

    public function nextOrThrow()
    {
        $count = $this->getCount();

        if ($count >= static::MAX_REQUESTS) {
            throw new HttpException("Rate limit", 429);
        }
    }
}
