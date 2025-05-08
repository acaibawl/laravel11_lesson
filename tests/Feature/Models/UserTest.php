<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_greeting(): void
    {
        $user = User::factory()->create(['name' => '与太郎']);
        $sentence = $user->greeting();
        $this->assertSame('与太郎さん、こんにちは！', $sentence);
    }

    public function test_hasMany_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Collection::class, $user->posts);
        $this->assertInstanceOf(Post::class, $user->posts->first());;
    }
}
