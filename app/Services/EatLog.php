<?php

declare(strict_types=1);

namespace App\Services;

class EatLog
{
    public function eat(string $food)
    {
        \Log::info("{$food}を食べました。");
    }
}
