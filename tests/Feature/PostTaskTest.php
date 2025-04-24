<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function testPostTask()
    {
        $name = 'new task';

        $response = $this->postJson('/api/tasks', [
            'name' => $name,
        ]);

        $id = $response->json(['id']);

        $response->assertStatus(201);
        $response->assertJson([
            'id' => $id,
            'name' => $name,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $id,
            'name' => $name,
        ]);
    }

    public function testPostTaskValidationError()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }
}
