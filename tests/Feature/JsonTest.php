<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JsonTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/jsonTest');

        // テストに成功（success しか検証していないが、OK）
        $response->assertJson([
            'success' => true,
        ]);

        // テストに成功（きっかり同じ）
        $response->assertExactJson([
            'success' => true,
            'username' => 'John',
            'cat' => [1, 2, 3],
        ]);

        // テストに成功（cat と success の順番は入れ替わったが成功）
        $response->assertExactJson([
            'cat' => [1, 2, 3],
            'username' => 'John',
            'success' => true,
        ]);

        // テストに失敗
        $response->assertExactJson([
            'success' => true,
            'username' => 'John',
            'cat' => [3, 2, 1],  // ここの並び順で失敗する
        ]);

        // テストに失敗
        $response->assertExactJson([
            'success' => 1, // 1ではダメ
            'username' => 'John',
            'cat' => [1, 2, 3],
        ]);

        // テストに失敗（successしかない）
        $response->assertExactJson([
            'success' => true,
        ]);
    }
}
