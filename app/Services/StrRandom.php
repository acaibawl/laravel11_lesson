<?php

namespace App\Services;

use Illuminate\Support\Str;
use OutOfRangeException;

class StrRandom
{
    public function get(int $length): string
    {
        if ($length < 1 || 100 < $length) {
            throw new OutOfRangeException('文字数は1から100の間で指定してください。');
        }

        return Str::random($length);
    }
}
