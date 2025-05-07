<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MemberControllerTest extends TestCase
{
    /**
     * ログインしていない場合、authルートにリダイレクトされる
     */
    public function test_when_not_logged_in_redirect_to_auth_screen(): void
    {
        $response = $this->get('/members');
        $response->assertRedirectToRoute('auth');
    }

    public function test_when_logged_in_screen_message()
    {
        $user = User::factory()->create(['name' => '与太郎']);

        $response = $this->actingAs($user)->get('/members');

        $response
            ->assertOk()
            ->assertSee('ようこそ与太郎さん！');
    }
}
