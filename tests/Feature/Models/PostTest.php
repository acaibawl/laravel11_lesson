<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_belongsTo_user(): void
    {
        $post = Post::factory()->create();

        $instance = $post->user;

        $this->assertInstanceOf(User::class, $instance);
    }
}
