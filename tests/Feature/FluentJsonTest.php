<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FluentJsonTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // 配列postsの中身もAssertableJsonで検証する書き方
        $this->get('/api/fluentJsonTest')
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) =>
                $json->where('name', 'Yamada')
                    ->hasAll(['age', 'gender'])
                    ->where('email', fn ($email) => str_ends_with($email, '@example.net'))
                    ->missing('password')
                    ->has('posts', 2, fn (AssertableJson $json) =>
                        $json->where('title', 'title1')
                            ->etc() // bod要素の存在チェックを省く
                    )->has('posts.1', fn (AssertableJson $json) =>
                        $json->where('title', 'title2')
                            ->etc()
                    )
            );

        // 配列postsの中身をwhereで検証する書き方
        $this->get('/api/fluentJsonTest')
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) =>
            $json->where('name', 'Yamada')
                ->hasAll(['age', 'gender'])
                ->where('email', fn ($email) => str_ends_with($email, '@example.net'))
                ->missing('password')
                ->where('posts', [
                    ['title' => 'title1', 'body' => 'body1'],
                    ['title' => 'title2', 'body' => 'body2'],
                ])
            );
    }
}
