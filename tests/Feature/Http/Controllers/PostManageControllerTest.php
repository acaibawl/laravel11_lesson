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
}
