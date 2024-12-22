<?php declare(strict_types=1);

namespace App\Interfaces;

use App\HttpResponse;

interface ControllerInterface
{
    public static function response(): HttpResponse;
}
