<?php

namespace App\Services;

use Illuminate\Support\Str;

class StrRandom
{
    public function get(int $length): string
    {
        return Str::random($length);
    }
}
