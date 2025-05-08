<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use OutOfRangeException;
use Tests\TestCase;

class ThrowExceptionTest extends TestCase
{
    /**
     * 例外が起こることのテスト
     */
    public function test_throw_exception()
    {
        $this->withoutExceptionHandling();
        $this->expectException(OutOfRangeException::class);
        $this->get('/throw-exception');
    }
}
