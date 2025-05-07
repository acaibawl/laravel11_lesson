<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $post = Post::factory()->create();
        $this->dumpdb();
    }

    public function test_post_2_count()
    {
        $taro = User::factory()->create(['name' => 'Taro']);
        $posts = Post::factory()->count(2)->create(['user_id' => $taro->id]);

        // とあるユーザーが「こんにちは世界」という内容のポストを1件所有している
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'body' => 'こんにちは世界',
        ]);

        // 3名のユーザーが、それぞれ何件かブログ投稿を所有している
        $users = User::factory()->count(3)->create()->each(function (User $user) {
            Post::factory()->count(rand(2, 3))->create(['user_id' => $user->id]);
        });

        // 10件の記事を$usersのuserにランダムにアタッチする。
        Post::factory()->count(10)->recycle($users)->create();
    }
}
