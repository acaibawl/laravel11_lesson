<?php

namespace Tests\Feature;

use App\Services\StrRandom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
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

    /**
     * 秘密鍵が出力される
     */
    public function test_secret_key_is_output()
    {
        $mock = Mockery::mock(StrRandom::class);
        $mock->shouldReceive('get')->once()->with(10)->andReturn('HELLO WORLD');
        $this->instance(StrRandom::class, $mock);

        $response = $this->get('/get-random-string');

        $response
            ->assertOk()
            ->assertSee('HELLO WORLD');

    }
}
