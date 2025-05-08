<?php

namespace Tests\Unit\Services;

use App\Services\StrRandom;
use PHPUnit\Framework\TestCase;

class StrRandomTest extends TestCase
{
    /**
     * 指定の文字数を返す
     */
    public function test_return_specified_number_of_characters()
    {
        $random = New StrRandom();

        $str5 = $random->get(5);
        $str10 = $random->get(10);

        $this->assertSame(5, strlen($str5));
        $this->assertSame(10, strlen($str10));
    }

    /*
     * ランダムな文字列を返す
     */
    public function test_returns_a_random_string()
    {
        $random = New StrRandom();

        $str1 = $random->get(10);
        $str2 = $random->get(10);

        $this->assertNotSame($str1, $str2);
    }
}
