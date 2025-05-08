<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_show_status_1(): void
    {
        $openPost = Post::factory()->create(['body' => '公開中のポスト本文']);
        $closedPost = Post::factory()->closed()->create(['body' => '非公開中のポスト本文']);

        $response = $this->get('/posts');

        $response->assertOk()
            ->assertSee($openPost->body)
            ->assertDontSee($closedPost->body);
    }
}
