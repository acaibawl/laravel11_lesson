<?php

namespace Tests\Unit\Services;

use App\Services\StrRandom;
use OutOfRangeException;
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

    /**
     * ランダムな文字列を返す
     */
    public function test_returns_a_random_string()
    {
        $random = New StrRandom();

        $str1 = $random->get(10);
        $str2 = $random->get(10);

        $this->assertNotSame($str1, $str2);
    }

    /**
     * 文字数範囲外の為、例外発生
     */
    public function test_exception_raised_due_to_character_count_out_of_range()
    {
        $random = New StrRandom();

        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage('文字数は1から100の間で指定してください。');

        $random->get(101);
    }
}
