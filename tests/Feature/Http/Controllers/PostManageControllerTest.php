<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostManageControllerTest extends TestCase
{
    public function test_only_my_posts_showed()
    {
        $me = $this->login();

        Post::factory()->for($me)->create(['body' => '私のブログ本文']);
        Post::factory()->create(['body' => '他人様のブログ本文']);

        $response = $this->get('/members/posts');

        $response
            ->assertOk()
            ->assertSee('私のブログ本文')
            ->assertDontSee('他人様のブログ本文');
    }

    public function test_can_create_my_new_post()
    {
        $me = $this->login();

        $validData = [
            'body' => '私のブログ本文',
            'status' => '1',
        ];

        $response = $this->post(route('posts.store'), $validData);
        $post = Post::first();
        $response->assertRedirectToRoute('posts.edit', ['post' => $post])
            ->assertSessionHas('status', 'ブログを投稿しました');
        // リダイレクト後

        $this->assertDatabaseHas('posts',
            [...$validData, 'user_id' => $me->id]
        );

    }
}
