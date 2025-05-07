<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_show_index(): void
    {
        // 表示するuserを準備している
        $user = User::factory()->create(['name' => '織田信長']);

        $response = $this->get('/users');

        $response->assertOk()
            ->assertSee($user->name);

    }
}
